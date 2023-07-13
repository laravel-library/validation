<?php

namespace Dingo\Validation;

use Dingo\Validation\Parameters\Contacts\ParameterFactory;
use Dingo\Validation\Parameters\Generator;
use Dingo\Validation\Validation\ExtraData;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\Validator;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->bindingSingle();

        $this->registerDepends();
    }

    protected function bindingSingle(): void
    {
        $this->app->bind(Store::class, fn() => new ExtraData());

        $this->app->bind(ParameterFactory::class, fn() => new Generator());
    }

    protected function registerDepends(): void
    {
        $this->app->when(Validator::class)
            ->needs(Store::class)
            ->give(Store::class);

        $this->app->when(Validator::class)
            ->needs(ParameterFactory::class)
            ->give(ParameterFactory::class);
    }
}