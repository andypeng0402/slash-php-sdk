<?php

namespace SlashPhpSdk\Exceptions;

/**
 * SDK基础异常类
 */
class BaseException extends \Exception
{
    protected $request;
    protected $response;
    protected $body;

    public function __construct(
        string $message = "",
        $request = null,
        $response = null,
        $body = null
    ) {
        parent::__construct($message);
        $this->request = $request;
        $this->response = $response;
        $this->body = $body;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getBody()
    {
        return $this->body;
    }
}