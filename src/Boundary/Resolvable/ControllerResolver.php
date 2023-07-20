<?php

namespace Dingo\Validation\Boundary\Resolvable;

use Dingo\Query\Contacts\Resolvable;
use Illuminate\Database\Eloquent\Model;

class ControllerResolver implements Resolvable
{

    public function binding(string $class): void
    {
        // TODO: Implement binding() method.
    }

    public function getConcrete(): Model
    {
        // TODO: Implement getConcrete() method.
    }
}