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