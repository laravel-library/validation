<?php

namespace Dingo\Validation;

use Dingo\Validation\Validation\ExtraData;
use Dingo\Validation\Validation\ValidateScene;
use Dingo\Validation\Validation\Contacts\Scene;
use Dingo\Validation\Validation\Contacts\Store;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->bindingSingle();
    }

    protected function bindingSingle(): void
    {
        $this->app->bind(Scene::class, fn() => new ValidateScene());

        $this->app->bind(Store::class, fn() => new ExtraData());
    }
}