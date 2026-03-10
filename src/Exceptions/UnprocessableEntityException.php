<?php

namespace SlashPhpSdk\Exceptions;

class UnprocessableEntityException extends ApiStatusException
{
    public const STATUS_CODE = 422;
}