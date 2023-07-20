<?php

namespace Dingo\Validation\Transmit\Contacts;

use Closure;

interface Transfer
{
    public function get(string $name): mixed;

    public function except(array $attribute): self;

    public function filter(Closure $closure = null): self;

    public function values(): array;
}