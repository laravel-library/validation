<?php

namespace Tests\Unit\Provider;

use Dingo\Validation\Factory\ValidatorFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\SceneManager;
use Dingo\Validation\Validation\SceneValidator;
use Dingo\Validation\ValidationServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ServiceProviderTest extends TestCase
{
    #[DataProvider('container')]
    public function testMakeScene(Container $container): void
    {
        $sceneInstance = $container->make(SceneManager::class);

        $this->assertInstanceOf(Scene::class, $sceneInstance);
    }

    #[DataProvider('container')]
    public function testMakeValidator(Container $app):void
    {
        $factory = $app->make(ExampleRequest::class);

        dd($factory);





    }

    public static function container(): array
    {
        $app = Container::getInstance();

        $service = new ValidationServiceProvider($app);

        $service->register();

        return [
            [$app],
        ];
    }
}