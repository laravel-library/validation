<?php

namespace Elephant\Validation\Contacts\Validation;

interface Scene
{

    public function hasScene(string $scene): bool;

    public function getScene(string $scene): array;

    public function scenes(): array;

}