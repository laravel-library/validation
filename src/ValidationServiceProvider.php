<?php

declare(strict_types=1);

namespace Elephant\Validation;

use Elephant\Validation\Commands\ValidatorCommand;
use Elephant\Validation\Contacts\Resources\DataTransfer;
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
        $this->app->singleton(DataTransfer::class, fn(): DataTransfer => new FormDataResource());

        $this->app->singleton(
            SceneValidatable::class,
            fn(Container $app): SceneValidatable => $app->make(SceneManager::class)
        );
    }

    public function boot(): void
    {
        $this->commands($this->commands);
    }
}