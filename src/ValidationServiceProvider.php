<?php

namespace Koala\Validation;

use Koala\Guesses\Contacts\Guessable;
use Koala\Guesses\Guesses\ValidatorGuesser;
use Koala\Validation\Commands\ValidatorCommand;
use Koala\Validation\Factory\Contacts\Factory;
use Koala\Validation\Factory\SceneFactory;
use Koala\Validation\Factory\ValidatableFactory;
use Koala\Validation\Scenes\Contacts\Scene;
use Koala\Validation\Scenes\SceneManager;
use Koala\Validation\Store\Contacts\DataAccess;
use Koala\Validation\Store\Store;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    protected array $commands = [
        ValidatorCommand::class,
    ];

    public function register(): void
    {
        $this->bindings();

        $this->commands($this->commands);
    }

    protected function bindings(): void
    {
        $this->app->bind(DataAccess::class, fn() => new Store());

        $this->app->singleton(Scene::class, SceneManager::class);

        $this->app->bind(Factory::class, SceneFactory::class);

        $this->app->when(SceneManager::class)
            ->needs(Factory::class)
            ->give(ValidatableFactory::class);

        $this->app->when(ValidatableFactory::class)
            ->needs(Guessable::class)
            ->give(ValidatorGuesser::class);
    }
}