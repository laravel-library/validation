<?php

namespace Dingo\Validation\Validation\Contacts;

interface Store
{
    public function isEmpty(): bool;

    public function merge(array $values): array;

    public function store(array $values): void;
}