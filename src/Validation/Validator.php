<?php

declare(strict_types=1);

namespace Elephant\Validation\Validation;

use Exception;
use Elephant\Validation\Contacts\Resources\Resourceable;
use Elephant\Validation\Contacts\Validation\Scene\SceneValidatable;
use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\Scene;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator as ValidatorContacts;

abstract class Validator extends FormRequest implements Validatable, Scene
{
    use ValidateWhenSceneTrait;

    protected readonly Resourceable $resource;

    protected readonly SceneValidatable $scene;

    private bool $autoValidate;

    public function __construct(
        Resourceable     $resource,
        SceneValidatable $scene,
        array            $query = [],
        array            $request = [],
        array            $attributes = [],
        array            $cookies = [],
        array            $files = [],
        array            $server = [],
        mixed            $content = null,
        bool             $autoValidate = false
    )
    {
        $this->resource = $resource;

        $this->autoValidate = $autoValidate;

        $this->scene = $scene;

        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    public function validateForm(): Resourceable
    {
        if ($this->resource->isNotEmpty()) {
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

    abstract public function rules(): array;
}