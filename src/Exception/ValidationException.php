<?php

namespace Elephant\Validation\Exception;

use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use RuntimeException;

class ValidationException extends RuntimeException
{
    public function __construct(string $message = "", int $code = 500, ?Throwable $previous = null)
    {

        $code = match (true) {
            $previous instanceof \Illuminate\Validation\ValidationException => 422,
            $previous instanceof AuthorizationException                     => 403,
        };

        parent::__construct($message, $code, $previous);
    }
}