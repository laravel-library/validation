<?php

namespace Dingo\Validation\Validation;

use Dingo\Validation\Contacts\Store;

final class ExtraData implements Store
{
    protected array $values = [];

    public function isEmpty(): bool
    {
        return empty($this->values);
    }

    public function merge(array $values): array
    {
        return array_merge($values, $this->values);
    }

    public function store(array $values): void
    {
        $this->values = $values;
    }
}