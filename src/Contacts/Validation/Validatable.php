<?php

namespace Elephant\Validation\Contacts\Validation;

use Elephant\Validation\Contacts\Resources\DataTransfer;

interface Validatable
{
    public function rules(): array;

    public function hasRuleMethod(string $name): bool;

    public function validateForm(): DataTransfer;

    public function validateRaw(): array;
}