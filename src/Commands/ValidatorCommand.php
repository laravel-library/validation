<?php

declare(strict_types=1);

namespace Elephant\Validation\Commands;

use Illuminate\Console\GeneratorCommand;

class ValidatorCommand extends GeneratorCommand
{

    protected $name = 'make:validator';

    protected $description = 'Create a new request validator.';

    protected $type = 'validator';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/' . $this->type . '.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\Http\\Requests';
    }
}