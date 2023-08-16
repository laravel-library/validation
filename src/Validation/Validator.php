<?php

declare(strict_types=1);

namespace Elephant\Validation\Validation;

use Exception;
use Elephant\Validation\Contacts\Resources\Resourceable;
use Elephant\Validation\Contacts\Validation\Scene\Scene;
use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\ValidateWhenScene;
use Illuminate\Validation\Factory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\Validator as AbstractValidator;
use Illuminate\Contracts\Validation\Validator as ValidatorContacts;

abstract class Validator extends FormRequest implements Validatable, ValidateWhenScene
{
    use ValidateWhenSceneTrait;

    protected readonly Resourceable $resource;

    protected readonly Scene $scene;

    private bool $autoValidate;

    public function __construct(
        Resourceable $resource,
        Scene        $scene,
        array        $query = [],
        array        $request = [],
        array        $attributes = [],
        array        $cookies = [],
        array        $files = [],
        array        $server = [],
        mixed        $content = null,
        bool         $autoValidate = false
    )
    {
        $this->resource = $resource;

        $this->autoValidate = $autoValidate;

        $this->scene = $scene;

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function validateForm(): Resourceable
    {
        if (!$this->resource->isEmpty()) {
            $this->resource->flush();
        }

        return $this->resource->extra($this->validateRaw());
    }

    public function validateRaw(): array
    {
        try {

            $formData = $this->resolveValidator()->validated();

        } catch (ValidationException $validationException) {
            $this->failedValidationException($validationException);
        }

        return $formData;
    }

    final public function validateResolved(): void
    {
        if ($this->autoValidate) {
            $this->resolveValidator();
        }
    }

    private function resolveValidator(): ValidatorContacts
    {
        try {
            if (!$this->passesAuthorization()) {
                $this->failedAuthorization();
            }

            $instance = $this->getValidatorInstance();

            if ($instance->fails()) {
                $this->failedValidation($instance);
            }

        } catch (ValidationException $validationException) {
            $this->failedValidationException($validationException);
        } catch (AuthorizationException $e) {
            $this->failedValidationException($e);
        }

        return $instance;
    }

    private function failedValidationException(Exception $exception): never
    {
        throw new \Elephant\Validation\Exception\ValidationException(message: $exception->getMessage(), previous: $exception);
    }


    final public function validator(Factory $factory): AbstractValidator
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