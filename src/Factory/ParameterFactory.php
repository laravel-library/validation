<?php

namespace Dingo\Validation\Factory;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Parameters\Contacts\Parameter;
use Dingo\Validation\Parameters\FormParameter;

final readonly class ParameterFactory implements Factory
{
    public function make(mixed $values): Parameter
    {
        return new FormParameter($values);
    }
}