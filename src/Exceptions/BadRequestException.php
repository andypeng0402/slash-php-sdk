<?php

namespace SlashPhpSdk\Exceptions;

class BadRequestException extends ApiStatusException
{
    public const STATUS_CODE = 400;
}