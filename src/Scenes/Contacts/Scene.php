<?php

namespace Dingo\Validation\Scenes\Contacts;

use Dingo\Validation\Validation\Contacts\Validatable;

interface Scene
{
    public function withScene(string $scene): Validatable;

    public function withRule(array|string $rule): Validatable;

    public function replaceRules(Validatable $validatable): array;

    public function hasRule(): bool;

    public function hasScene(): bool;
}