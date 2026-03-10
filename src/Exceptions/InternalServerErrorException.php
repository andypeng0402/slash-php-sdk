<?php

namespace SlashPhpSdk\Exceptions;

class InternalServerErrorException extends ApiStatusException
{
    public const STATUS_CODE = 500;
}