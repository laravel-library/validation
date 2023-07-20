<?php

namespace Dingo\Validation\Scenes\Contacts;

use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Validation\Contacts\ValidatesWhenScene;

interface Scene
{
    public function withScene(string $scene): Validatable;

    public function withRule(array|string $rule): Validatable;

    public function replaceRules(Validatable|ValidatesWhenScene $validatable): array;

    public function merge(Validatable $validatable): array;

    public function hasRule(): bool;

    public function hasScene(): bool;
}