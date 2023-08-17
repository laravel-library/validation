<?php

namespace Elephant\Validation\Contacts\Validation\Scene;

use Elephant\Validation\Contacts\Validation\Validatable;
use Elephant\Validation\Contacts\Validation\Scene;

interface SceneValidatable extends Scene
{
    public function replaceRules(Validatable $validatable): array;

    public function merge(Validatable $validatable): array;

    public function hasRule(): bool;

    public function hasScene(): bool;
}