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
use Dingo\Validation\Store\Contacts\DataAccess;
use Dingo\Validation\Store\Store;
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
        $this->app->bind(DataAccess::class, fn() => new Store());

        $this->app->singleton(Scene::class, SceneManager::class);

        $this->app->bind(Factory::class, SceneFactory::class);

        $this->app->when(ValidatableFactory::class)
            ->needs(Guessable::class)
            ->give(ControllerGuesser::class);
    }
}