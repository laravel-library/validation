<?php

namespace Tests\Unit\Store;

use Koala\Validation\Store\Contacts\DataAccess;
use Koala\Validation\Store\Store;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StoreTest extends TestCase
{
    #[DataProvider('make')]
    public function testStore(DataAccess $access): void
    {
        $access->store('a');
        $access->store('b');
        $access->store(['c', 'd']);

        $this->assertNotEmpty($access->raw());
    }

    public static function make(): array
    {
        return [
            [new Store()],
        ];
    }
}