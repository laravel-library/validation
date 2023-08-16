<?php

namespace Elephant\Validation\Commands;

use Illuminate\Console\GeneratorCommand;

class ValidatorCommand extends GeneratorCommand
{

    protected $signature = 'make:validator';

    protected $description = 'Create a new request validator.';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/validator.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\Http\\Requests';
    }
}