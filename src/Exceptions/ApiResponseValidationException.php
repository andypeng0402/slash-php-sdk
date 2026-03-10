<?php

namespace SlashPhpSdk\Exceptions;

/**
 * @param \Psr\Http\Message\ResponseInterface|null $response
 */
class ApiResponseValidationException extends BaseException
{
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