<?php

namespace Dingo\Validation\Validation\Contacts;

use Dingo\Validation\Parameters\Contacts\Parameter;

interface Validatable
{
    public function rules(): array;

    public function scenes(): array;

    public function hasRule(string $attribute): bool;

    public function extra(array $values): self;

    public function validateForm(): Parameter;
}