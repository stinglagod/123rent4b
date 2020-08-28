<?php

namespace rent\entities\Shop\Product\Movement;

use rent\entities\behaviors\ClientBehavior;
use rent\entities\Client\Site;
use rent\entities\Shop\Product\Product;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $dateTime
 * @property integer $product_id
 * @property integer $qty
 * @property integer $site_id
 * @property integer $movement_id
 * @property integer $typeMovement_id
 *
 * @property Product $product
 * @property Movement $movement
**/

class Balance extends ActiveRecord
{

    public static function create(int $dateTime,int $productId, int $qty, int $typeMovementId): self
    {
        $balance = new static();
        $balance->dateTime=$dateTime;
        $balance->product_id=$productId;
        $balance->qty=$qty;
        $balance->typeMovement_id=$typeMovementId;
        return $balance;
    }
//    public function edit(int $begin, int $end=null,int $qty, int $productId, int $type_id, $name=''): void
//    {
//        $this->date_begin=$begin;
//        $this->date_end=$end;
//        $this->qty=$qty;
//        $this->product_id=$productId;
//        $this->type_id=$type_id;
//        $this->name=$name;
//    }


    public function getMovement(): ActiveQuery
    {
        return $this->hasOne(Movement::class, ['id' => 'movement_id']);
    }
    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
##############################################
    public static function tableName(): string
    {
        return '{{%shop_balance}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
        ];
    }
}