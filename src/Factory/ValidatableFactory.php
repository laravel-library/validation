<?php

namespace Elephant\Validation\Factory;

use Elephant\Query\Contacts\Queryable;
use Elephant\Support\Guesser\Contacts\Guessable;
use Elephant\Validation\Factory\Contacts\Factory;
use Elephant\Validation\Factory\Exceptions\ValidateNotFoundException;
use Elephant\Validation\Factory\Exceptions\ValidatorInheritanceException;
use Elephant\Validation\Validation\Contacts\Validatable;
use Elephant\Validation\Validation\Contacts\ValidatesWhenScene;
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