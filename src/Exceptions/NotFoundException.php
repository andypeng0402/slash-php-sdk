<?php

namespace SlashPhpSdk\Exceptions;

class NotFoundException extends ApiStatusException
{
    public const STATUS_CODE = 404;
}