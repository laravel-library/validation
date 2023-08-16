<?php

namespace Elephant\Validation;


use Elephant\Validation\Commands\ValidatorCommand;
use Elephant\Validation\Factory\Contacts\Factory;
use Elephant\Validation\Factory\SceneFactory;
use Elephant\Validation\Factory\ValidatableFactory;
use Elephant\Validation\Scenes\Contacts\Scene;
use Elephant\Validation\Scenes\SceneManager;
use Elephant\Validation\Store\Contacts\DataAccess;
use Elephant\Validation\Store\Store;
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
    }
}