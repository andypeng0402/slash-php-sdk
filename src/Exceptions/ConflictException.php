<?php

namespace SlashPhpSdk\Exceptions;

class ConflictException extends ApiStatusException
{
    public const STATUS_CODE = 409;
}