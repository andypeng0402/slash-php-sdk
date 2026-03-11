<?php

namespace SlashPhpSdk\Resources;

/**
 * VirtualAccountResource类，用于管理虚拟账户相关操作 
 */
class VirtualAccountResource extends BaseResource
{
    /**
     * 获取账户列表
     */
    public function list(array $params = []): array
    {
        return $this->get('/virtual-account', $params);
    }

    /**
     * 获取特定账户信息
     */
    public function retrieve(string $virtualAccountId): array
    {
        return $this->get("/virtual-account/{$virtualAccountId}");
    }

    /**
     * 创建新账户
     */
    public function create(array $data): array
    {
        return $this->post('/virtual-account', $data);
    }

    /**
     * 更新账户信息
     */
    public function update(string $virtualAccountId, array $data): array
    {
        return $this->put("/virtual-account/{$virtualAccountId}", $data);
    }
}