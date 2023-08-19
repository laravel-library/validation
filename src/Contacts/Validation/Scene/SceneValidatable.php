<?php

namespace Elephant\Validation\Contacts\Validation\Scene;

use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\Scene;
use Elephant\Validation\Contacts\Validation\ValidateWhenScene;

interface SceneValidatable extends Scene
{
    public function refreshRules(Validatable|ValidateWhenScene|Scene $validatable): array;

    public function mergeRules(Validatable|ValidateWhenScene|Scene $validatable): array;

    public function hasRule(): bool;

    public function hasScene(): bool;
}