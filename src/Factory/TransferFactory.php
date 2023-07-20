<?php

namespace Dingo\Validation\Factory;

use Dingo\Validation\Transmit\Transmit;
use Dingo\Validation\Factory\Contacts\Factory;
use Dingo\Validation\Transmit\Contacts\Transfer;
use Illuminate\Contracts\Container\Container;

final readonly class TransferFactory implements Factory
{
    protected Container $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function make(mixed $dependency): Transfer
    {
        return new Transmit($dependency);
    }
}