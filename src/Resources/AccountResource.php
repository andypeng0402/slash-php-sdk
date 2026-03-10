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
        return $this->get('/accounts', $params);
    }

    /**
     * 获取特定账户信息
     */
    public function retrieve(string $accountId): array
    {
        return $this->get("/accounts/{$accountId}");
    }

    /**
     * 创建新账户
     */
    public function create(array $data): array
    {
        return $this->post('/accounts', $data);
    }

    /**
     * 更新账户信息
     */
    public function update(string $accountId, array $data): array
    {
        return $this->put("/accounts/{$accountId}", $data);
    }

    /**
     * 删除账户
     */
    public function delete(string $accountId, array $params = []): array
    {
        return $this->delete("/accounts/{$accountId}", $params);
    }
}