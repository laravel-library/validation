<?php

namespace Dingo\Validation;

use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Boundary\Guesses\ControllerGuesser;
use Dingo\Validation\Commands\ValidatorCommand;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\SceneFactory;
use Dingo\Validation\Factory\ValidatableFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\SceneManager;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\ExtraData;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    protected array $commands = [
        ValidatorCommand::class,
    ];

    public function register(): void
    {
        $this->registerSingle();

        $this->commands($this->commands);
    }

    protected function registerSingle(): void
    {
        $this->app->singleton(Store::class, new ExtraData());

        $this->app->bind(Scene::class, SceneManager::class);

        $this->app->when(ValidatableFactory::class)
            ->needs(Guessable::class)
            ->give(ControllerGuesser::class);

        $this->app->singleton(Factory::class, fn(Container $container) => new SceneFactory($container));
    }
}