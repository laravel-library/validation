<?php

namespace Tests\Unit;

use Dingo\Validation\ValidationServiceProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application;

trait Bootstrap
{

    public static function getContainer(): array
    {
        self::register(self::bootstrap());

        return [
            [self::bootstrap()],
        ];
    }

    public static function register(Application $application): void
    {
        $serviceProvider = new ValidationServiceProvider($application);

        $application->bind(Container::class, fn(Container $container) => $container);

        $serviceProvider->register();
    }

    public static function bootstrap(): Application
    {
        return Application::getInstance();
    }
}