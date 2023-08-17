<?php

namespace Elephant\Validation\Exception;

use RuntimeException;
use Throwable;

class ValidationInheritanceException extends RuntimeException
{
    public function __construct(string $message = "", int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}