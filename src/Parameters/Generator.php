<?php

namespace Dingo\Validation\Parameters;

use Dingo\Validation\Parameters\Contacts\ParameterFactory;
use Dingo\Validation\Parameters\Contacts\Parameter;

class Generator implements ParameterFactory
{

    public function parameter(array $formData): Parameter
    {
        return new FormParameter($formData);
    }
}