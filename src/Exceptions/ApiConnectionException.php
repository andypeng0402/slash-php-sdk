<?php

namespace SlashPhpSdk\Exceptions;

class ApiConnectionException extends BaseException
{
    public function __construct(string $message = "Connection error.", $request = null)
    {
        parent::__construct($message, $request);
    }
}