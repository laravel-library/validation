<?php

namespace Dingo\Validation\Factory;

use Dingo\Query\Contacts\Queryable;
use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\Exceptions\ValidateNotFoundException;
use Dingo\Validation\Factory\Exceptions\ValidatorInheritanceException;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Validation\Contacts\ValidatesWhenScene;
use Illuminate\Contracts\Container\Container;

final readonly class ValidatableFactory implements Factory
{
    protected Container $app;

    protected Guessable $guessable;

    public function __construct(Container $container, Guessable $guessable)
    {
        $this->app = $container;

        $this->guessable = $guessable;
    }

    public function make(mixed $dependency): Validatable
    {
        return $this->app->make($this->prepareValidator($dependency), [
            'autoValidate' => false,
        ]);
    }

    protected function prepareValidator(string $class): string
    {
        $class = $this->guessable->guess($class)->getResolved();

        if (!class_exists($class)) {
            $this->validateNotFound($class);
        }

        if (!is_subclass_of($class, ValidatesWhenScene::class)) {
            $this->unableValidateForm();
        }

        return $class;
    }

    protected function validateNotFound(string $class): never
    {
        throw new ValidateNotFoundException("Validator dont exists [$class].");
    }

    protected function unableValidateForm(): never
    {
        throw new ValidatorInheritanceException('Unable validate form.');
    }
}