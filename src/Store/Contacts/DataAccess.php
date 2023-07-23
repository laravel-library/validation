<?php

namespace Koala\Validation\Store\Contacts;

interface DataAccess
{
    public function isEmpty(): bool;

    public function merge(array $values): array;

    public function store(array|string $values): void;

    public function raw(): array;
}