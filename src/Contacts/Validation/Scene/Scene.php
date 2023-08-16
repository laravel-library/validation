<?php

namespace Elephant\Validation\Contacts\Validation\Scene;

use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\ValidateWhenScene;

interface Scene extends ValidateWhenScene
{
    public function replaceRules(Validatable $validatable): array;

    public function merge(Validatable $validatable): array;

    public function hasRule(string $attribute): bool;

    public function hasScene(): bool;
}