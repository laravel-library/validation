<?php

namespace Dingo\Validation\Validation;

use Dingo\Validation\Parameters\Contacts\Parameter;
use Dingo\Validation\Parameters\FormParameter;
use Dingo\Validation\Validation\Contacts\Scene;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator as Factory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class Validator extends FormRequest implements Validatable, Scene
{

    protected Scene $scene;

    protected Store $store;

    private bool $autoValidate;

    public function __construct(
        Scene $scene,
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
        $this->scene = $scene;

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

    public function extend(array|string $rule): Scene
    {
        return $this->scene->extend($rule);
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

    private function prepareRules(): array
    {
        $rules = $this->rules();

        if (!empty($this->extendRules)) {
            foreach ($this->extendRules as $extend) {
                if (method_exists($this, $extendRules = "{$extend}Rules")) {
                    $rules = array_merge($rules, $this->{$extendRules}());
                }
            }
        }

        $scenes = $this->replace ? $this->scenes() : $this->scenes;

        if ($this->currentScene && isset($scenes[$this->currentScene])) {
            $sceneFields = is_array($scenes[$this->currentScene])
                ? $scenes[$this->currentScene]
                : explode(',', $scenes[$this->currentScene]);

            return array_reduce($sceneFields, function (mixed $carry, $field) use ($rules) {
                if (array_key_exists($field, $rules)) {
                    $carry[$field] = $rules[$field];
                }

                return $carry;
            }, []);
        }

        return $rules;
    }

    abstract public function rules(): array;
}