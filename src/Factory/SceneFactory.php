<?php

namespace Koala\Validation\Factory;

use Koala\Validation\Scenes\Contacts\Scene;
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
        return $this->app->make(Scene::class);
    }
}