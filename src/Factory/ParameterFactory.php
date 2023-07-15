<?php

namespace Dingo\Validation\Factory;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Parameters\Contacts\Parameter;
use Dingo\Validation\Parameters\FormParameter;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Contracts\Container\Container;

final readonly class ParameterFactory implements Factory
{
    protected Container $container;

    public function __construct(Container $app)
    {
        $this->container = $app;
    }

    public function make(): Parameter
    {
        return new FormParameter($this->getValidator()->validateRaw());
    }

    protected function getValidator(): Validatable
    {
        return $this->container->get(Validatable::class);
    }
}