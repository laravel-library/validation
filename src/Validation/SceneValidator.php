<?php

declare(strict_types=1);

namespace Dingo\Validation\Validation;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Transmit\Contacts\Transfer;
use Dingo\Validation\Store\Contacts\DataAccess;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class SceneValidator extends FormRequest implements Validatable
{

    protected readonly DataAccess $dataAccess;

    protected readonly Factory $factory;

    protected readonly Factory $sceneFactory;

    private bool $autoValidate;

    public function __construct(
        DataAccess $store,
        Factory    $factory,
        Factory    $sceneFactory,
        array      $query = [],
        array      $request = [],
        array      $attributes = [],
        array      $cookies = [],
        array      $files = [],
        array      $server = [],
        mixed      $content = null,
        bool       $autoValidate = true
    )
    {

        $this->dataAccess = $store;

        $this->factory = $factory;

        $this->autoValidate = $autoValidate;

        $this->sceneFactory = $sceneFactory;

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function extra(array $values): Validatable
    {
        $this->dataAccess->store($values);

        return $this;
    }

    public function validateForm(): Transfer
    {
        return $this->factory->make($this->validateRaw());
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
            ? $this->scene->merge()
            : $this->rules();

        return $this->scene->hasScene()
            ? $this->scene->replaceRules()
            : $rules;
    }

    abstract public function rules(): array;
}