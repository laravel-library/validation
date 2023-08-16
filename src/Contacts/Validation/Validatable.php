<?php

namespace Elephant\Validation\Contacts\Validation;

use Elephant\Validation\Contacts\Resources\Resourceable;

interface Validatable
{
    public function rules(): array;

    public function validateForm(): Resourceable;

    public function validateRaw(): array;
}