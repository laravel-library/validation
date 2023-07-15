<?php

namespace Dingo\Validation\Factory;

use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Contracts\Container\Container;

final readonly class ValidatorFactory implements Factory
{
    protected Container $container;

    protected Guessable $guessable;

    public function __construct(Container $container, Guessable $guessable)
    {
        $this->container = $container;

        $this->guessable = $guessable;
    }

    public function make(): Validatable
    {
        return $this->container->make(Validatable::class, ['autoValidate' => false]);
    }
}