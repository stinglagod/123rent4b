<?php

namespace rent\cart\storage;

use rent\cart\CartItem;
use rent\entities\Shop\Product\Product;
use yii\db\Connection;
use yii\db\Query;

class DbStorage implements StorageInterface
{
    private $userId;
    private $db;
    private $siteId;

    public function __construct($userId, $siteId, Connection $db)
    {
        $this->userId = $userId;
        $this->db = $db;
        $this->siteId = $siteId;
    }

    public function load(): array
    {
        $rows = (new Query())
            ->select('*')
            ->from('{{%shop_cart_items}}')
            ->where([
                'user_id' => $this->userId,
                'site_id' => $this->siteId
            ])
            ->orderBy(['product_id' => SORT_ASC])
            ->all($this->db);

        return array_map(function (array $row) {
            /** @var Product $product */
            if ($product = Product::find()->active()->andWhere(['id' => $row['product_id']])->one()) {
                return new CartItem($row['type_id'],$row['quantity'],null,$product->getPriceByType($row['type_id']),$product,null,null,true);
            }
            return false;
        }, $rows);
    }

    public function save(array $items): void
    {
        $this->db->createCommand()->delete('{{%shop_cart_items}}', [
            'user_id' => $this->userId,
            'site_id' => $this->siteId
        ])->execute();

        $this->db->createCommand()->batchInsert(
            '{{%shop_cart_items}}',
            [
                'user_id',
                'product_id',
                'type_id',
                'quantity',
                'site_id'
            ],
            array_map(function (CartItem $item) {
                return [
                    'user_id' => $this->userId,
                    'product_id' => $item->getProductId(),
                    'type_id' => $item->getType(),
                    'quantity' => $item->getQuantity(),
                    'site_id' => $this->siteId,
                ];
            }, $items)
        )->execute();
    }
} 