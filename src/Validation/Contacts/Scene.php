<?php

namespace Dingo\Validation\Contacts;

interface Scene
{
    public function scenes(): array;

    public function withScene(string $name): self;

    public function extend(array|string $rule): self;
}