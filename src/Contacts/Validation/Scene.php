<?php

namespace Elephant\Validation\Contacts\Validation;

interface Scene
{

    public function hasScene(string $scene): bool;

    public function scenes(): array;
}