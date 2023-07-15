<?php

namespace Dingo\Validation;

use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Boundary\Guesses\ControllerGuesser;
use Dingo\Validation\Commands\ValidatorCommand;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\ParameterFactory;
use Dingo\Validation\Factory\ValidatorFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\ValidateScene;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\ExtraData;
use Dingo\Validation\Validation\SceneValidator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton(Store::class, fn() => new ExtraData());

        $this->app->singleton(Scene::class, ValidateScene::class);

    }

    protected function registerDepends(): void
    {
        $this->app->when(SceneValidator::class)
            ->needs(Factory::class)
            ->give(ParameterFactory::class);

        $this->app->when(ValidateScene::class)
            ->needs(Factory::class)
            ->give(fn(Application $app) => new ValidatorFactory($app,new ControllerGuesser()));
    }
}