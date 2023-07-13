<?php

namespace Dingo\Validation\Contacts;

interface Validatable
{
    public function extra(array $values): self;

    public function validateForm(): Parameter;
}