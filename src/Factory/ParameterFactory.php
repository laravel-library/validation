<?php

namespace Dingo\Validation\Factory;

use Dingo\Validation\Parameters\FormParameter;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Parameters\Contacts\Parameter;
use Illuminate\Contracts\Container\Container;

final readonly class ParameterFactory implements Factory
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function make(mixed $dependency): Parameter
    {
        return new FormParameter($dependency);
    }
}