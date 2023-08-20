<?php

namespace Elephant\Validation\Contacts\Validation;

interface Scene
{

    public function hasScene(string $scene): bool;

    public function getScene(string $scene): string|array;

    public function scenes(): array;

}