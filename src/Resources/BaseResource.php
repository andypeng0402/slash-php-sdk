<?php

namespace SlashPhpSdk\Resources;

use SlashPhpSdk\Client;

/**
 * 资源基类，所有API资源类都应该继承此类
 */
abstract class BaseResource
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * 获取客户端实例
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * 发送GET请求到API
     */
    protected function get(string $endpoint, array $params = []): array
    {
        return $this->client->get($endpoint, $params);
    }

    /**
     * 发送POST请求到API
     */
    protected function post(string $endpoint, array $data = []): array
    {
        return $this->client->post($endpoint, $data);
    }

    /**
     * 发送PUT请求到API
     */
    protected function put(string $endpoint, array $data = []): array
    {
        return $this->client->put($endpoint, $data);
    }

    /**
     * 发送DELETE请求到API
     */
    protected function delete(string $endpoint, array $params = []): array
    {
        return $this->client->delete($endpoint, $params);
    }
}