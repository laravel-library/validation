<?php

namespace Dingo\Validation\Store;

use Dingo\Validation\Store\Contacts\DataAccess;

final class Store implements DataAccess
{
    protected array $values = [];

    public function isEmpty(): bool
    {
        return empty($this->values);
    }

    public function store(array|string $values): void
    {
        $this->values = is_string($values)
            ? $this->merge([$values])
            : $this->merge($values);
    }

    public function merge(array $values): array
    {
        return array_merge($this->values, $values);
    }

    public function raw(): array
    {
        return $this->values;
    }
}