<?php

declare(strict_types=1);

namespace Dingo\Validation\Validation;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Parameters\Contacts\Parameter;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class SceneValidator extends FormRequest implements Validatable
{

    protected readonly Store $store;

    protected readonly Factory $factory;

    protected readonly Scene $scene;

    private bool $autoValidate;

    public function __construct(
        Store   $store,
        Factory $factory,
        Scene   $scene,
        array   $query = [],
        array   $request = [],
        array   $attributes = [],
        array   $cookies = [],
        array   $files = [],
        array   $server = [],
        mixed   $content = null,
        bool    $autoValidate = true
    )
    {

        $this->store = $store;

        $this->factory = $factory;

        $this->autoValidate = $autoValidate;

        $this->scene = $scene;

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function extra(array $values): Validatable
    {
        $this->store->store($values);

        return $this;
    }

    public function validateForm(): Parameter
    {
        return $this->factory->make();
    }

    public function validateRaw(): array
    {
        $formData = $this->resolveValidator()->validated();

        return $this->store->isEmpty() ? $formData : $this->store->merge($formData);
    }

    final public function validateResolved(): void
    {
        if ($this->autoValidate) {
            $this->resolveValidator();
        }
    }

    private function resolveValidator(): Validator
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

    public function scenes(): array
    {
        return [];
    }

    public function hasRule(string $attribute): bool
    {
        return array_key_exists($attribute, $this->rules());
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
        $rules = $this->scene->hasRule()
            ? $this->scene->merge($this->rules())
            : $this->rules();

        return $this->scene->hasScene()
            ? $this->scene->replaceRules($this)
            : $rules;
    }

    abstract public function rules(): array;
}