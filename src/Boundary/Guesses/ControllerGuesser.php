<?php

namespace Dingo\Validation\Boundary\Guesses;

use Dingo\Support\Guesser\Guesser;

class ControllerGuesser extends Guesser
{
    public function getResolved(): string
    {
        $class = "{$this->class}Request";

        return class_exists($class) ? $class : "{$this->class}FormRequest";
    }

    protected function hasSuffix(string $name): bool
    {
        return str_ends_with($name, $this->suffix());
    }

    protected function replaceSuffix(string $clazz): string
    {
        return substr($clazz, 0, -strlen($this->suffix()));
    }

    protected function bind(string $class): void
    {
        $this->class = 'App\\Http\\Requests\\' . $class;
    }

    protected function suffix(): string|array
    {
        return 'Controller';
    }
}