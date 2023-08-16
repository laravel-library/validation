<?php

declare(strict_types=1);

namespace Elephant\Validation\Validation;

use Elephant\Validation\Scenes\Contacts\Scene;
use Elephant\Validation\Transmit\Contacts\Transfer;
use Elephant\Validation\Store\Contacts\DataAccess;
use Elephant\Validation\Transmit\Transmit;
use Elephant\Validation\Validation\Contacts\Validatable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;

abstract class SceneValidator extends FormRequest implements Validatable
{

    protected readonly DataAccess $dataAccess;

    protected readonly Scene $scene;

    private bool $autoValidate;

    public function __construct(
        DataAccess $store,
        Scene      $scene,
        array      $query = [],
        array      $request = [],
        array      $attributes = [],
        array      $cookies = [],
        array      $files = [],
        array      $server = [],
        mixed      $content = null,
        bool       $autoValidate = false
    )
    {
        $this->dataAccess = $store;

        $this->autoValidate = $autoValidate;

        $this->scene = $scene;

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function extra(array $values): Validatable
    {
        $this->dataAccess->store($values);

        return $this;
    }

    public function validateForm(): Transfer
    {
        return new Transmit($this->validateRaw());
    }

    public function validateRaw(): array
    {
        $formData = $this->resolveValidator()->validated();

        return $this->dataAccess->isEmpty() ? $formData : $this->dataAccess->merge($formData);
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

    public function hasRule(string $attribute): bool
    {
        return array_key_exists($attribute, $this->rules());
    }

    final public function validator(Factory $factory): \Illuminate\Validation\Validator
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
            ? $this->scene->merge($this)
            : $this->rules();

        return $this->scene->hasScene()
            ? $this->scene->replaceRules($this)
            : $rules;
    }

    abstract public function rules(): array;
}