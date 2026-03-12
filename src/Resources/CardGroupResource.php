<?php
namespace SlashPhpSdk\Resources;

/**
 * CardGroupResource类，用于管理卡片组相关操作 
 */
class CardGroupResource extends BaseResource
{
    /**
     * 获取卡片组列表
     */
    public function list(array $params = []): array
    {
        return $this->get('/card-group', $params);
    }
    /**
     * 获取特定卡片组信息
     */
    public function retrieve(string $cardGroupId): array
    {
        return $this->get("/card-group/{$cardGroupId}");
    }
    /**
     * 创建新卡片组
     */
    public function create(array $data): array
    {
        return $this->post('/card-group', $data);
    }
    /**
     * 更新卡片组信息
     */
    public function update(string $cardGroupId, array $data): array
    {
        return $this->patch("/card-group/{$cardGroupId}", $data);
    }

    /**
     * 部分更新卡片组消费限额
     */
    public function updateSpendingConstraint(string $cardGroupId, array $data): array
    {
        return $this->patch("/card-group/{$cardGroupId}/spending-constraint", $data);
    }
    
    /**
     * 部分替换卡片组消费限额
     */
    public function replaceSpendingConstraint(string $cardGroupId, array $data): array
    {
        return $this->put("/card-group/{$cardGroupId}/spending-constraint", $data);
    }

    /**
     * 获取卡片组使用率
     */
    public function utilization(string $cardGroupId): array
    {
        return $this->get("/card-group/{$cardGroupId}/utilization");
    }

}