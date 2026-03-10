<?php

namespace SlashPhpSdk\Exceptions;

class PermissionDeniedException extends ApiStatusException
{
    public const STATUS_CODE = 403;
}