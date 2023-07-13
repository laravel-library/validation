<?php

namespace Dingo\Validation\Validation;

use Closure;
use Dingo\Validation\Parameters\FormParameter;
use Dingo\Validation\Validation\Contacts\Scene;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Parameters\Contacts\Parameter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator as Factory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

abstract class Validator extends FormRequest implements Validatable, Scene
{

    protected readonly Store $store;

    protected ?string $scene = null;

    protected array $extends = [];

    private bool $autoValidate;

    public function __construct(
        Store $store,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        mixed $content = null,
        bool  $autoValidate = true
    )
    {

        $this->store = $store;

        $this->autoValidate = $autoValidate;

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function extra(array $values): Validatable
    {
        $this->store->store($values);

        return $this;
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function validateForm(): Parameter
    {
        $formData = $this->resolveValidator()->validated();

        $formData = $this->store->isEmpty() ? $formData : $this->store->merge($formData);

        return new FormParameter($formData);
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    final public function validateResolved(): void
    {
        if ($this->autoValidate) {
            $this->resolveValidator();
        }
    }

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    private function resolveValidator(): Factory
    {
        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();
        if ($instance->fails()) {
            $this->failedValidation($instance);
        }

        return $instance;
    }

    final public function validator(\Illuminate\Validation\Factory $factory): \Illuminate\Validation\Validator
    {
        return $factory->make(
            $this->validationData(),
            $this->prepareValidateRules(),
            $this->messages(),
            $this->attributes()
        );
    }

    private function prepareValidateRules(): array
    {
        $rules = $this->rules();

        if ($this->hasRules()) {
            $rules = array_merge($rules, $this->getExtendRules());
        }

        if (!$this->hasScene()) {
            return $rules;
        }

        if ($this->sceneExists()) {
            return $this->getNewRules();
        }

        return $rules;
    }

    private function getExtendRules(): array
    {
        return array_reduce($this->extends, function (array $extends, string $extend) {

            if (method_exists($this, "{$extend}Rules")) {
                $extends = array_merge($extends, $this->{"{$extend}Rules"}());
            }

            return $extends;
        }, []);
    }

    private function sceneExists(): bool
    {
        return isset($this->scenes()[$this->scene]);
    }

    protected function getNewRules(): array
    {
        return array_reduce($this->resolveAttributes(), $this->combine(), []);
    }

    private function combine(): Closure
    {
        return function (array $newRules, string $attribute): array {
            if (array_key_exists($attribute, $this->rules())) {
                $newRules[$attribute] = $this->rules()[$attribute];
            }

            return $newRules;
        };
    }

    private function resolveAttributes(): array
    {
        $scene = $this->scenes()[$this->scene];

        return match (true) {
            is_string($scene) => explode(',', $scene),
            default           => $scene
        };
    }

    public function withScene(string $name): Scene
    {
        $this->scene = $name;

        return $this;
    }

    public function extend(array|string $rule): Scene
    {
        if (is_array($rule)) {
            $this->extends = $this->filter($rule);
        }

        if (is_string($rule)) {
            $this->extends[] = Str::camel($rule);
        }

        return $this;
    }

    protected function filter(array $rules): array
    {
        return array_merge(
            $this->extends,
            array_map(fn(string $rule) => Str::camel($rule), $rules),
        );
    }

    public function hasRules(): bool
    {
        return !empty($this->extends);
    }

    public function hasScene(): bool
    {
        return !is_null($this->scene);
    }

    public function scenes(): array
    {
        return [];
    }

    abstract public function rules(): array;
}