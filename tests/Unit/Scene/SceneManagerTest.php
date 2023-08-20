<?php

namespace Tests\Unit\Scene;

use Elephant\Validation\Contacts\Validation\Scene;
use Koala\Validation\Factory\Contacts\Factory;
use Koala\Validation\Scenes\SceneManager;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Bootstrap;

class SceneManagerTest extends TestCase implements Scene
{
    use Bootstrap;

    #[DataProvider('getContainer')]
    public function testMakeSceneManager(Application $application): void
    {
        $sceneFactory = $application->make(Factory::class);

        $this->assertInstanceOf(SceneManager::class, $sceneFactory->make('App\Http\Controllers\TestController'));
    }

    #[DataProvider('getContainer')]
    public function testSceneManagerSetCurrentScene(Application $application): void
    {
        $sceneManager = $application->make(Factory::class)->make('');

        $sceneManager->withScene('test');

        $this->assertTrue($sceneManager->hasScene());
    }

    public function scenes(): array
    {
        // TODO: Implement scenes() method.
    }

    public function hasScene(string $scene): bool
    {
        // TODO: Implement hasScene() method.
    }
}