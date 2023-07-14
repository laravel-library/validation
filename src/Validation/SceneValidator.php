<?php

declare(strict_types=1);

namespace Dingo\Validation\Validation;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\ParameterFactory;
use Dingo\Validation\Parameters\Contacts\Parameter;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class SceneValidator extends FormRequest implements Validatable
{

    protected readonly Store $store;

    protected readonly Factory $factory;

    private bool $autoValidate;

    public function __construct(
        Store            $store,
        ParameterFactory $factory,
        array            $query = [],
        array            $request = [],
        array            $attributes = [],
        array            $cookies = [],
        array            $files = [],
        array            $server = [],
        mixed            $content = null,
        bool             $autoValidate = true
    )
    {

        $this->store = $store;

        $this->factory = $factory;

        $this->autoValidate = $autoValidate;

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

    abstract public function rules(): array;
}