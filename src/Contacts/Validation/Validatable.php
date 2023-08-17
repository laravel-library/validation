<?php

namespace Elephant\Validation\Contacts\Validation;

use Elephant\Validation\Contacts\Resources\Resourceable;

interface Validatable
{
    public function rules(): array;

    public function hasRule(string $attribute): bool;

    public function hasRuleMethod(string $name): bool;

    public function validateForm(): Resourceable;

    public function validateRaw(): array;
}