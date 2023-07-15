<?php

namespace Dingo\Validation;

use Dingo\Validation\Commands\ValidatorCommand;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\ParameterFactory;
use Dingo\Validation\Factory\ValidatorFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\ValidateScene;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\ExtraData;
use Dingo\Validation\Validation\SceneValidator;
use Illuminate\Contracts\Container\Container;
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
        $this->app->singleton(Store::class, fn() => new ExtraData());

        $this->app->singleton(Scene::class, fn(Container $app) => new ValidateScene(new ValidatorFactory($app)));
    }

    protected function registerDepends(): void
    {
        $this->app->when(SceneValidator::class)
            ->needs(Factory::class)
            ->give(ParameterFactory::class);
    }
}