<?php

namespace Elephant\Validation\Contacts\Validation;

interface ValidateWhenScene
{
    public function withScene(string $scene): ValidateWhenScene|Validatable;

    public function withRule(string $rule): ValidateWhenScene|Validatable;
}