<?php

namespace Elephant\Validation\Contacts\Validation;

interface Scene
{
    public function withScene(string $scene): self;

    public function withRule(string $rule): self;
}