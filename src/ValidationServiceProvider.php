<?php

namespace Dingo\Validation;

use Dingo\Validation\Boundary\Guesses\ControllerGuesser;
use Dingo\Validation\Commands\ValidatorCommand;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\ParameterFactory;
use Dingo\Validation\Factory\ValidatorFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\SceneManager;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Validation\ExtraData;
use Dingo\Validation\Validation\SceneValidator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;

class ValidationServiceProvider extends ServiceProvider
{
    protected array $commands = [
        ValidatorCommand::class,
    ];

    public function register(): void
    {
        $this->bindingSingle();

        $this->registerDepends();

        $this->commands($this->commands);
    }

    protected function bindingSingle(): void
    {
        $this->app->bind(Validatable::class, SceneValidator::class);
    }

    protected function registerDepends(): void
    {
        $this->app->when(SceneManager::class)
            ->needs(Factory::class)
            ->give(fn(Container $app) => new ValidatorFactory($app, new ControllerGuesser()));

        $this->app->when(SceneValidator::class)
            ->needs(Factory::class)
            ->give(fn(Container $app) => new ParameterFactory($app));

        $this->app->when(SceneValidator::class)
            ->needs(Store::class)
            ->give(fn() => new ExtraData());

        $this->app->when(SceneValidator::class)
            ->needs(Scene::class)
            ->give(SceneManager::class);
    }
}