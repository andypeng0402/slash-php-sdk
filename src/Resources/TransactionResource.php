<?php

namespace SlashPhpSdk\Resources;

/**
 * Transaction资源类，用于管理交易相关操作
 */
class TransactionResource extends BaseResource
{
    /**
     * 获取交易列表
     */
    public function list(array $params = []): array
    {
        return $this->get('/transactions', $params);
    }

    /**
     * 获取特定交易信息
     */
    public function retrieve(string $transactionId): array
    {
        return $this->get("/transactions/{$transactionId}");
    }

    /**
     * 创建新交易
     */
    public function create(array $data): array
    {
        return $this->post('/transactions', $data);
    }

    /**
     * 更新交易信息
     */
    public function update(string $transactionId, array $data): array
    {
        return $this->put("/transactions/{$transactionId}", $data);
    }

    /**
     * 删除交易
     */
    public function delete(string $transactionId , array $data = []): array
    {
        return $this->delete("/transactions/{$transactionId}", $data);
    }
}