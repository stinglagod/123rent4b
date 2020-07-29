<?php

namespace rent\entities\User;

use yii\db\ActiveRecord;
use rent\entities\behaviors\ClientBehavior;

/**
 * @property integer $user_id
 * @property integer $product_id
 */
class WishlistItem extends ActiveRecord
{
    public static function create($productId)
    {
        $item = new static();
        $item->product_id = $productId;
        return $item;
    }

    public function isForProduct($productId): bool
    {
        return $this->product_id == $productId;
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%user_wishlist_items}}';
    }
}