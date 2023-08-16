<?php

namespace Elephant\Validation\Scenes\Contacts;

use Elephant\Validation\Validation\Contacts\Validatable;
use Elephant\Validation\Validation\Contacts\ValidatesWhenScene;

interface Scene
{
    public function withScene(string $scene): Validatable;

    public function withRule(array|string $rule): Validatable;

    public function replaceRules(Validatable|ValidatesWhenScene $validatable): array;

    public function merge(Validatable $validatable): array;

    public function hasRule(): bool;

    public function hasScene(): bool;
}