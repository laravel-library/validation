<?php

declare(strict_types=1);

namespace Elephant\Validation;

use Elephant\Validation\Commands\ValidatorCommand;
use Elephant\Validation\Contacts\Resources\Resourceable;
use Elephant\Validation\Contacts\Validation\Scene\SceneValidatable;
use Elephant\Validation\Resources\FormDataResource;
use Elephant\Validation\Scenes\SceneManager;
use Illuminate\Contracts\Container\Container;
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
        $this->app->bind(Resourceable::class, fn(): Resourceable => new FormDataResource());

        $this->app->singleton(
            SceneValidatable::class,
            fn(Container $app): SceneValidatable => $app->make(SceneManager::class)
        );
    }
}