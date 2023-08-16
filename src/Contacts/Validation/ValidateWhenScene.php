<?php

namespace Elephant\Validation\Contacts\Validation;

interface ValidateWhenScene
{
    public function withScene(string $scene): self;

    public function withRule(string $rule): self;

    public function hasRule(string $attribute): bool;
}