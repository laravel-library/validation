<?php

namespace Dingo\Validation\Validation\Contacts;

use Dingo\Validation\Parameters\Contacts\Parameter;

interface Validatable
{
    public function extra(array $values): self;

    public function validateForm(): Parameter;
}