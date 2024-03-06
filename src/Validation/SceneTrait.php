<?php

declare(strict_types=1);

namespace Elephant\Validation\Validation;

trait SceneTrait
{
    public function hasScene(string $scene): bool
    {
        return isset($this->scenes()[$scene]);
    }

    public function getScene(string $scene): array
    {
        $attributes = $this->scenes()[$scene];

        if (is_string($attributes)) {
            return str_contains($attributes, ',') ? explode(',', $attributes) : [$attributes];
        }

        return $attributes;
    }
}