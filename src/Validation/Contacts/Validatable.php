<?php

namespace Elephant\Validation\Validation\Contacts;

use Elephant\Validation\Transmit\Contacts\Transfer;

interface Validatable
{
    public function rules(): array;

    public function hasRule(string $attribute): bool;

    public function extra(array $values): self;

    public function validateForm(): Transfer;

    public function validateRaw(): array;
}