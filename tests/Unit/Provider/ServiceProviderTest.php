<?php

namespace Tests\Unit\Provider;

use Dingo\Validation\Factory\ValidatorFactory;
use Dingo\Validation\Scenes\Contacts\Scene;
use Dingo\Validation\Scenes\ValidateScene;
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
        $sceneInstance = $container->make(ValidateScene::class);

        $this->assertInstanceOf(Scene::class, $sceneInstance);
    }

    #[DataProvider('container')]
    public function testMakeValidateFactory(Container $app):void
    {
        $factory = $app->make(ValidatorFactory::class);

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