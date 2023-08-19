<?php

namespace Elephant\Validation\Contacts\Validation;

interface Scene
{
    public function withScene(string $scene): Scene|Validatable;

    public function withRule(string $rule): Scene|Validatable;
}