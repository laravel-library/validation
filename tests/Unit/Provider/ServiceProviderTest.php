<?php

namespace Tests\Unit\Provider;

use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Boundary\Guesses\ControllerGuesser;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\SceneFactory;
use Dingo\Validation\Factory\ValidatableFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\SceneManager;
use Dingo\Validation\Validation\Contacts\Store;
use Dingo\Validation\Validation\ExtraData;
use Illuminate\Container\Container;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{

    #[DataProvider('container')]
    public function testFactory(Container $app): void
    {
        $factory = $app->make(Factory::class);

        dd($factory->make("App\\Http\\Controllers\\ExampleController"));

    }

    public static function container(): array
    {
        $app = Container::getInstance();

        $app->bind(\Illuminate\Contracts\Container\Container::class, fn($app) => $app);

        $app->singleton(Store::class, fn() => new ExtraData());
        $app->bind(Scene::class, SceneManager::class);

        $app->when(ValidatableFactory::class)
            ->needs(Guessable::class)
            ->give(ControllerGuesser::class);

        $app->bind(Factory::class, SceneFactory::class);

        return [
            [$app],
        ];
    }
}