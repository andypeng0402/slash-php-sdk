<?php

namespace SlashPhpSdk;

use SlashPhpSdk\Resources;

/**
 * Slash PHP SDK主类
 * 提供对所有API资源的访问
 */
class SlashPhpSdk
{
    private $client;

    // 资源实例缓存
    private $resources = [];

    public function __construct(array $config = [])
    {
        $this->client = new Client($config);
    }

    /**
     * 获取Account资源实例
     */
    public function account(): Resources\AccountResource
    {
        if (!isset($this->resources['account'])) {
            $this->resources['account'] = new Resources\AccountResource($this->client);
        }
        return $this->resources['account'];
    }
    /**
     * 获取Account资源实例
     */
    public function virtualAccount(): Resources\VirtualAccountResource
    {
        if (!isset($this->resources['virtualAccount'])) {
            $this->resources['virtualAccount'] = new Resources\VirtualAccountResource($this->client);
        }
        return $this->resources['virtualAccount'];
    }

    /**
     * 获取Transaction资源实例
     */
    public function transaction(): Resources\TransactionResource
    {
        if (!isset($this->resources['transaction'])) {
            $this->resources['transaction'] = new Resources\TransactionResource($this->client);
        }
        return $this->resources['transaction'];
    }

    /**
     * 获取Card资源实例
     */
    public function card(): Resources\CardResource
    {
        if (!isset($this->resources['card'])) {
            $this->resources['card'] = new Resources\CardResource($this->client);
        }
        return $this->resources['card'];
    }
    
       /**
     * 获取cardGroup资源实例
     */
    public function cardGroup(): Resources\CardGroupResource
    {
        if (!isset($this->resources['cardGroup'])) {
            $this->resources['cardGroup'] = new Resources\CardGroupResource($this->client);
        }
        return $this->resources['cardGroup'];
    }

    /**
     * 获取底层客户端
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * 获取API密钥
     */
    public function getApiKey(): ?string
    {
        return $this->client->getApiKey();
    }

    /**
     * 获取承载令牌
     */
    public function getBearerToken(): ?string
    {
        return $this->client->getBearerToken();
    }

    /**
     * 设置新的API密钥
     */
    public function setApiKey(string $apiKey): void
    {
        $this->client->setApiKey($apiKey);
    }

    /**
     * 设置新的承载令牌
     */
    public function setBearerToken(string $bearerToken): void
    {
        $this->client->setBearerToken($bearerToken);
    }
}