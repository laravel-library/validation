<?php

namespace Dingo\Validation\Factory;

use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\SceneManager;
use Illuminate\Contracts\Container\Container;

final readonly class SceneFactory implements Contacts\Factory
{

    protected Container $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function make(mixed $dependency): Scene
    {
        return new SceneManager($this->makeFactory()->make($dependency));
    }

    protected function makeFactory(): Factory
    {
        return $this->app->make(ValidatableFactory::class);
    }
}