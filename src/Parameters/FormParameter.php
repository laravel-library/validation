<?php

namespace Dingo\Validation\Parameters;

use Closure;
use Dingo\Validation\Parameters\Contacts\Parameter;

final class FormParameter implements Parameter
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

    public function except(array $attribute): Parameter
    {
        $this->fromData = $this->filterFromData(fn(mixed $value, string $key) => !in_array($value, $attribute));

        return $this;
    }

    public function filter(Closure $closure = null): Parameter
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