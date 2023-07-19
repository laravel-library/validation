<?php

namespace Tests\Unit\Provider;

use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Boundary\Guesses\ControllerGuesser;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Factory\SceneFactory;
use Dingo\Validation\Factory\ValidatableFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\SceneManager;
use Dingo\Validation\Store\Contacts\DataAccess;
use Dingo\Validation\Store\Store;
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