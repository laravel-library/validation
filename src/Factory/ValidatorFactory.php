<?php

namespace Dingo\Validation\Factory;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Contracts\Container\Container;

final readonly class ValidatorFactory implements Factory
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function make(): Validatable
    {
        return $this->container->make(Validatable::class, ['autoValidate' => false]);
    }
}