<?php

namespace Dingo\Validation\Validation;

use Dingo\Validation\Contacts\Scene;

final class ValidateScene implements Scene
{
    protected ?string $scene = null;



    public function scenes(): array
    {
        // TODO: Implement scenes() method.
    }

    public function withScene(string $name): Scene
    {
        // TODO: Implement withScene() method.
    }

    public function extend(array|string $rule): Scene
    {
        // TODO: Implement extend() method.
    }
}