<?php

namespace Tests\Unit\Guesses;

use Dingo\Support\Guesser\Contacts\Guessable;
use Dingo\Validation\Boundary\Guesses\ControllerGuesser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ControllerGuesserTest extends TestCase
{
    #[DataProvider('guesser')]
    public function testExample(Guessable $guessable): void
    {
        $request = $guessable->guess('App\Http\Controllers\Examples\UserController')
            ->getResolved();

        $this->assertEquals('App\Http\Requests\UserFormRequest', $request, $request);
    }

    public static function guesser(): array
    {
        return [
            [new ControllerGuesser()],
        ];
    }
}