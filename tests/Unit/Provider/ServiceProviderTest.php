<?php

namespace Tests\Unit\Provider;

use Koala\Support\Guesser\Contacts\Guessable;
use Koala\Validation\Boundary\Guesses\ControllerGuesser;
use Koala\Validation\Factory\Contacts\Factory;
use Koala\Validation\Factory\SceneFactory;
use Koala\Validation\Factory\ValidatableFactory;
use Koala\Validation\Scenes\Contacts\Scene;
use Koala\Validation\Scenes\SceneManager;
use Koala\Validation\Store\Contacts\DataAccess;
use Koala\Validation\Store\Store;
use Illuminate\Container\Container;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{

    #[DataProvider('container')]
    public function testFactory(Container $app): void
    {
        $factory = $app->make(Factory::class);

        $scene = $factory->make("App\Http\Controllers\ExampleController");

    }

    public static function container(): array
    {
        $app = Container::getInstance();

        $app->bind(\Illuminate\Contracts\Container\Container::class, fn($app) => $app);

        $app->singleton(DataAccess::class, fn() => new Store());
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