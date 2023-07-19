<?php

namespace Dingo\Validation\Factory;

use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\Exceptions\ValidateNotFoundException;
use Dingo\Validation\Factory\Exceptions\ValidatorInheritanceException;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Validation\Contacts\ValidatesWhenScene;
use Illuminate\Contracts\Container\Container;

final readonly class ValidatableFactory implements Factory
{
    protected Container $container;

    protected Guessable $guessable;

    public function __construct(Container $container, Guessable $guessable)
    {
        $this->container = $container;

        $this->guessable = $guessable;
    }

    public function make(mixed $dependency): Validatable
    {
        return $this->container->make($this->prepareValidator($dependency), [
            'sceneFactory' => $this->container->make(SceneFactory::class),
            'factory'      => $this->container->make(ParameterFactory::class),
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