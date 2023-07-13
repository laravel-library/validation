<?php

namespace Dingo\Validation\Parameters\Contacts;

interface ParameterFactory
{
    public function parameter(array $formData): Parameter;
}