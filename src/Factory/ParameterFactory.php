<?php

namespace Dingo\Validation\Factory;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Parameters\Contacts\Parameter;
use Dingo\Validation\Parameters\FormParameter;
use Dingo\Validation\Validation\Contacts\Validatable;
use Illuminate\Contracts\Container\Container;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final readonly class ParameterFactory implements Factory
{
    protected Container $container;

    public function __construct(Container $app)
    {
        $this->container = $app;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function make(): Parameter
    {
        return new FormParameter($this->getValidator()->validateRaw());
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getValidator(): Validatable
    {
        return $this->container->get(Validatable::class);
    }
}