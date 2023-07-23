<?php

namespace Koala\Validation\Scenes\Contacts;

use Koala\Validation\Validation\Contacts\Validatable;
use Koala\Validation\Validation\Contacts\ValidatesWhenScene;

interface Scene
{
    public function withScene(string $scene): Validatable;

    public function withRule(array|string $rule): Validatable;

    public function replaceRules(Validatable|ValidatesWhenScene $validatable): array;

    public function merge(Validatable $validatable): array;

    public function hasRule(): bool;

    public function hasScene(): bool;
}