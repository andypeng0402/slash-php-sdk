<?php

namespace SlashPhpSdk\Exceptions;

class RateLimitException extends ApiStatusException
{
    public const STATUS_CODE = 429;
}