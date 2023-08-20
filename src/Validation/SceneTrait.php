<?php

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

        return is_string($attributes) ? explode(',', $attributes) : $attributes;
    }
}