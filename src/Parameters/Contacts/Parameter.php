<?php

namespace Dingo\Validation\Parameters\Contacts;

use Closure;

interface Parameter
{
    public function get(string $name): mixed;

    public function except(array $attribute): self;

    public function filter(Closure $closure = null): self;

    public function values(): array;
}