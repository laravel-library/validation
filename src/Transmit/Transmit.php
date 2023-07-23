<?php

namespace Koala\Validation\Transmit;

use Closure;
use Koala\Validation\Transmit\Contacts\Transfer;

final class Transmit implements Transfer
{
    protected array $fromData;

    public function __construct(array $fromData)
    {
        $this->fromData = $fromData;
    }

    public function get(string $name): mixed
    {
        return $this->fromData[$name];
    }

    public function except(array $attribute): Transfer
    {
        $this->fromData = $this->filterFromData(fn(mixed $value, string $key) => !in_array($value, $attribute));

        return $this;
    }

    public function filter(Closure $closure = null): Transfer
    {
        $this->fromData = match (true) {
            $closure instanceof Closure => $this->filterFromData($closure),
            default                     => array_filter($this->fromData)
        };

        return $this;
    }

    protected function filterFromData(Closure $closure): array
    {
        return array_filter($this->fromData, fn(mixed $value, string $key) => $closure($value, $key), ARRAY_FILTER_USE_BOTH);
    }

    public function values(): array
    {
        return $this->fromData;
    }
}