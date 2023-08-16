<?php

namespace Elephant\Validation\Contacts\Resources;

use Closure;

interface Resourceable
{
    public function get(string $name): mixed;

    public function except(array $attribute): self;

    public function filter(Closure $closure = null): self;

    public function values(): array;

    public function isEmpty(): bool;

    public function extra(array $values): self;

    public function flush(): void;
}