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
        return $this->get('/transaction', $params);
    }

    /**
     * 获取交易聚合数据
     */
    public function aggregations(array $params = []): array
    {
        return $this->get('/transaction/aggregation', $params);
    }

    /**
     * 获取特定交易信息
     */
    public function retrieve(string $transactionId): array
    {
        return $this->get("/transaction/{$transactionId}");
    }

    /**
     * 更新交易信息
     */
    public function update(string $transactionId, array $data): array
    {
        return $this->patch("/transaction/{$transactionId}", $data);
    }

    /**
     * 获取交易费用详情
     */
    public function feeDetails(string $transactionId): array
    {
        return $this->get("/transaction/{$transactionId}/fee-details");
    }
}