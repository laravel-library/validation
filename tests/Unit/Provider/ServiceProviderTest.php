<?php

namespace Tests\Unit\Provider;

use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Boundary\Guesses\ControllerGuesser;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\ParameterFactory;
use Dingo\Validation\Factory\SceneFactory;
use Dingo\Validation\Factory\ValidatableFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\SceneManager;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\Contacts\Validatable;
use Dingo\Validation\Validation\ExtraData;
use Dingo\Validation\Validation\SceneValidator;
use Dingo\Validation\ValidationServiceProvider;
use Illuminate\Container\Container;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{

    #[DataProvider('container')]
    public function testFactory(Container $app): void
    {
        $factory = $app->make(Factory::class);

        dd($factory->make('App\\Http\\Controllers\\ExampleController'));
    }

    public static function container(): array
    {
        $app = Container::getInstance();

        $app->singleton(Store::class, fn() => new ExtraData());
        $app->bind(Scene::class, SceneManager::class);

        $app->when(ValidatableFactory::class)
            ->needs(Guessable::class)
            ->give(ControllerGuesser::class);

        $app->singleton(Factory::class, fn() => new SceneFactory($app));

        $app->bind(\Illuminate\Contracts\Container\Container::class, Container::class);

        return [
            [$app],
        ];
    }
}