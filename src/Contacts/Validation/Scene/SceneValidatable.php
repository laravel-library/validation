<?php

namespace Elephant\Validation\Contacts\Validation\Scene;

use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\ValidateWhenScene;
use Elephant\Validation\Contacts\Validation\Scene;

interface SceneValidatable extends ValidateWhenScene
{
    public function refreshRules(Validatable|Scene|ValidateWhenScene $validatable): array;

    public function mergeRules(Validatable|Scene|ValidateWhenScene $validatable): array;

    public function hasRule(): bool;

    public function hasScene(): bool;
}