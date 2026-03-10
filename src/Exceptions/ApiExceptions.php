<?php

namespace SlashPhpSdk\Exceptions;

/**
 * API相关异常类定义
 * @param \Psr\Http\Message\ResponseInterface|null $response
 */
class ApiStatusException extends BaseException
{
    public function __construct(
        string $message,
        $response = null,  // \Psr\Http\Message\ResponseInterface|null
        $body = null
    ) {
        parent::__construct($message, null, $response, $body);
        $this->response = $response;
    }

    public function getStatusCode(): ?int
    {
        return $this->response ? $this->response->getStatusCode() : null;
    }
}

class ApiConnectionException extends BaseException
{
    public function __construct(string $message = "Connection error.", $request = null)
    {
        parent::__construct($message, $request);
    }
}

class ApiResponseValidationException extends BaseException
{
    /**
     * @param \Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct(
        $response = null,  // \Psr\Http\Message\ResponseInterface|null
        $body = null,
        string $message = null
    ) {
        $msg = $message ?: "Data returned by API invalid for expected schema.";
        parent::__construct($msg, null, $response, $body);
        $this->response = $response;
    }

    public function getStatusCode(): ?int
    {
        return $this->response ? $this->response->getStatusCode() : null;
    }
}

class BadRequestException extends ApiStatusException
{
    public const STATUS_CODE = 400;
}

class AuthenticationException extends ApiStatusException
{
    public const STATUS_CODE = 401;
}

class PermissionDeniedException extends ApiStatusException
{
    public const STATUS_CODE = 403;
}

class NotFoundException extends ApiStatusException
{
    public const STATUS_CODE = 404;
}

class ConflictException extends ApiStatusException
{
    public const STATUS_CODE = 409;
}

class UnprocessableEntityException extends ApiStatusException
{
    public const STATUS_CODE = 422;
}

class RateLimitException extends ApiStatusException
{
    public const STATUS_CODE = 429;
}

class InternalServerErrorException extends ApiStatusException
{
    public const STATUS_CODE = 500;
}