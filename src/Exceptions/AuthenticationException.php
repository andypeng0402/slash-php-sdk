<?php

namespace SlashPhpSdk\Exceptions;

class AuthenticationException extends ApiStatusException
{
    public const STATUS_CODE = 401;
}