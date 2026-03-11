<?php

namespace SlashPhpSdk\Resources;

/**
 * Account资源类，用于管理账户相关操作
 */
class AccountResource extends BaseResource
{
    /**
     * 获取账户列表
     */
    public function list(array $params = []): array
    {
        return $this->get('/account', $params);
    }

    /**
     * 获取特定账户信息
     */
    public function retrieve(string $accountId): array
    {
        return $this->get("/account/{$accountId}");
    }

    /**
     * 创建新账户
     */
    public function create(array $data): array
    {
        return $this->post('/account', $data);
    }

    /**
     * 更新账户信息
     */
    public function update(string $accountId, array $data): array
    {
        return $this->put("/account/{$accountId}", $data);
    }

    /**
     * 获取账户余额
     */
    public function balance(string $accountId): array
    {
        return $this->get("/account/{$accountId}/balance");
    }
}