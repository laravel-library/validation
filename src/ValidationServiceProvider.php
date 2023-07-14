<?php

namespace Dingo\Validation;

use Dingo\Validation\Commands\ValidatorCommand;
use Dingo\Validation\Parameters\Contacts\ParameterFactory;
use Dingo\Validation\Parameters\Generator;
use Dingo\Validation\Validation\ExtraData;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\SceneValidator;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    protected array $commands = [
        ValidatorCommand::class,
    ];

    public function boot(): void
    {
        $this->bindingSingle();

        $this->registerDepends();

        $this->commands($this->commands);
    }

    protected function bindingSingle(): void
    {
        $this->app->bind(Store::class, fn() => new ExtraData());

        $this->app->bind(ParameterFactory::class, fn() => new Generator());
    }

    protected function registerDepends(): void
    {
        $this->app->when(SceneValidator::class)
            ->needs(Store::class)
            ->give(Store::class);

        $this->app->when(SceneValidator::class)
            ->needs(ParameterFactory::class)
            ->give(ParameterFactory::class);
    }
}