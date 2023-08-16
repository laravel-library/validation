<?php

namespace Elephant\Validation\Resources;

use Closure;
use Elephant\Validation\Contacts\Resources\Resourceable;

final class FormDataResource implements Resourceable
{
    protected array $fromData;

    public function __construct(array $fromData = [])
    {
        $this->fromData = $fromData;
    }

    public function get(string $name): mixed
    {
        return $this->fromData[$name];
    }

    public function except(array $attribute): Resourceable
    {
        $this->fromData = $this->filterFromData(fn(mixed $value, string $key): bool => !in_array($value, $attribute));

        return $this;
    }

    public function filter(Closure $closure = null): Resourceable
    {
        $this->fromData = $closure instanceof Closure
            ? $this->filterFromData($closure)
            : array_filter($this->fromData);

        return $this;
    }

    protected function filterFromData(Closure $closure): array
    {
        return array_filter($this->fromData, fn(mixed $value, string $key): bool => $closure($value, $key), ARRAY_FILTER_USE_BOTH);
    }

    public function values(): array
    {
        return $this->fromData;
    }

    public function extra(array $values): Resourceable
    {
        $this->fromData = $this->isEmpty() ? $values : array_merge($this->fromData, $values);

        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->fromData);
    }

    public function flush(): void
    {
        $this->fromData = [];
    }
}