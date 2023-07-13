<?php

declare(strict_types=1);

namespace Dingo\Validation\Validation;

use Dingo\Validation\Parameters\Contacts\ParameterFactory;
use Dingo\Validation\Validation\Contacts\Scene;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Parameters\Contacts\Parameter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator as Factory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class Validator extends FormRequest implements Validatable, Scene
{
    use SceneTrait;

    protected readonly Store $store;

    protected readonly ParameterFactory $factory;

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

    /**
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function validateForm(): Parameter
    {
        $formData = $this->resolveValidator()->validated();

        $formData = $this->store->isEmpty() ? $formData : $this->store->merge($formData);

        return $this->factory->parameter($formData);
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

    public function scenes(): array
    {
        return [];
    }

    abstract public function rules(): array;
}