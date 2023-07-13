<?php

namespace Dingo\Validation\Validation;

use Dingo\Validation\Contacts\Scene;
use Illuminate\Support\Str;

final class ValidateScene implements Scene
{
    protected ?string $scene = null;

    protected array $extends = [];

    public function scenes(): array
    {
        // TODO: Implement scenes() method.
    }

    public function withScene(string $name): Scene
    {
        $this->scene = $name;

        return $this;
    }

    public function extend(array|string $rule): Scene
    {
        if (is_array($rule)) {
            $this->extends = $this->filter($rule);
        }

        if (is_string($rule)) {
            $this->extends[] = Str::camel($rule);
        }

        return $this;
    }

    protected function filter(array $rules): array
    {
        return array_merge(
            $this->extends,
            array_map(fn(string $rule) => Str::camel($rule), $rules),
        );
    }

    public function hasRules(): bool
    {
        return !empty($this->extends);
    }
}