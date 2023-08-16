<?php

namespace Elephant\Validation\Factory\Exceptions;

use RuntimeException;
use Throwable;

class ValidateNotFoundException extends RuntimeException
{
    public function __construct(string $message = "", int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}