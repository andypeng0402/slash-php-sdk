<?php

namespace SlashPhpSdk\Resources;

/**
 * Card资源类，用于管理卡片相关操作
 */
class CardResource extends BaseResource
{
    /**
     * 获取卡片列表
     */
    public function list(array $params = []): array
    {
        return $this->get('/card', $params);
    }

    /**
     * 获取特定卡片信息
     */
    public function retrieve(string $cardId,array $params = [] , ?string $baseUrl = null): array
    {
        if(isset($params['include_pan']) || isset($params['include_cvv'])){
            if($params['include_pan'] === true || $params['include_cvv'] === true){
                $baseUrl = $$baseUrl ?: 'https://vault.slash.com';
            }
        }
        return $this->get("/card/{$cardId}", $params , $baseUrl ?? null);
    }

    /**
     * 创建新卡片
     */
    public function create(array $data): array
    {
        return $this->post('/card', $data);
    }

    /**
     * 更新卡片信息
     */
    public function update(string $cardId, array $data): array
    {
        return $this->patch("/card/{$cardId}", $data);
    }

    /**
     * 获取卡utilization
     */
    public function utilization(string $cardId): array
    {
        return $this->get("/card/{$cardId}/utilization");
    }

    /**
     * 更新卡片的spending constraint
     */
    public function updateSpendingConstraint(string $cardId, array $data): array
    {
        return $this->patch("/card/{$cardId}/spending-constraint", $data);
    }

    /**
     * 替换卡片的spending constraint
     */
    public function replaceSpendingConstraint(string $cardId, array $data): array
    {
        return $this->put("/card/{$cardId}/spending-constraint", $data);
    }

    /**
    * 获取卡片的modifier列表
    */
    public function getModifiers(string $cardId): array
    {
        return $this->get("/card/{$cardId}/modifier");
    }

    /**
     * 设置卡片的modifier
     */
    public function setModifier(string $cardId, array $data): array
    {
        return $this->put("/card/{$cardId}/modifier", $data);
    }

    /**
    * 获取所有卡片产品
    */
    public function allProducts(): array
    {
        return $this->get('/card-product');
    }
}