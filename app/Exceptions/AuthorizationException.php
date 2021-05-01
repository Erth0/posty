<?php

namespace App\Exceptions;

use Exception;

class AuthorizationException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
