<?php

declare(strict_types=1);

namespace Elephant\Validation\Resources;

use Closure;
use Elephant\Validation\Contacts\Resources\DataTransfer;

final class FormDataResource implements DataTransfer
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

    public function except(array $attribute): DataTransfer
    {
        $this->fromData = $this->filterFromData(fn(mixed $value, string $key): bool => !in_array($value, $attribute));

        return $this;
    }

    public function filter(Closure $closure = null): DataTransfer
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

    public function extra(array $values): DataTransfer
    {
        $this->fromData = $this->isNotEmpty() ? array_merge($this->fromData, $values) : $values;

        return $this;
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->fromData);
    }

    public function flush(): void
    {
        $this->fromData = [];
    }

    public function equal(string $name, mixed $expected): bool
    {
        if (!$this->has($name)) {
            return false;
        }

        if ($expected instanceof Closure) {
            return $expected($this->fromData[$name]);
        }

        return $this->fromData[$name] === $expected;
    }

    public function has(string $name): bool
    {
        return isset($this->fromData[$name]) && !empty($this->fromData[$name]);
    }
}