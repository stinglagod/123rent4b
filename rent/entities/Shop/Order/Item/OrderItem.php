<?php

namespace rent\entities\Shop\Order\Item;

use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Status;
use rent\entities\Shop\Product\Product;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $name
 * @property int $price
 * @property int $qty
 * @property int $period_qty
 * @property int $period_id
 * @property int $block_id
 * @property string $block_name
 * @property int $type_id
 * @property int $parent_id
 * @property string $note
 * @property int $current_status
 * @property int $sort
 * @property BlockData $blockData
 *
 * @property Order $order
 * @property OrderItem $parent
 * @property OrderItem[] $children
 */
class OrderItem extends ActiveRecord
{
    const TYPE_BLOCK=1;             //блок
    const TYPE_RENT=2;              //аренда
    const TYPE_SALE=3;              //продажа
    const TYPE_COLLECT=4;           //коллекция
    const TYPE_SERVICE=5;           //услуга
    const TYPE_CUSTOM=5;            //произвольная позиция

    public $blockData;

    public static function create(
        BlockData $blockData,
        PeriodData $periodData,
        $type_id,
        $price,
        $qty,
        $note,
        Product $product=null,
        $name=null,
        $parent_id=null,
        $createCustomer=false
    )
    {
        $item = new static();
        if ($product) {
            $item->name=$product->name;
        } else {
            $item->name=$name;
        }
        $item->qty=$qty;
        $item->price=$price;
        $item->period_qty=$periodData->qty;
        $item->period_id=$periodData->type;
        $item->block_id=$blockData->id;
        $item->block_name=$blockData->name;
        $item->type_id=$type_id;
        $item->parent_id=$parent_id;
        $item->note=$note;
        $item->current_status=$createCustomer?Status::NEW_BY_CUSTOMER:Status::NEW;

        $item->blockData=$blockData;

        return $item;
    }
    public static function createBlock($name):self
    {
        $item = new static();
        $item->name=$name;
        $item->type_id=self::TYPE_BLOCK;
        $item->blockData=new BlockData(null,$name);
        return $item;
    }
    public function editBlock($name):void
    {
        $this->block_name=$name;        //тем самым мы даем понять, что запись изменилсь. без этого не работает проведение SaveRelationsBehavior
        $this->blockData->name=$name;
    }

    public function getCost(): int
    {
        $cost=$this->price * $this->qty;
        switch ($this->type_id){
            case self::TYPE_COLLECT:
                foreach ($this->children as $child) {
                    $cost+=$child->getCost();
                }
                break;
            case self::TYPE_RENT:
                $cost*=$this->period_qty;
                break;
        }
        return $cost;
    }
    public function isBlockIdEqualTo($id):bool
    {
        return $this->block_id==$id;
    }
    public function setSort($sort): void
    {
        $this->sort = $sort;
    }
##############################################
    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Order::class, ['id'=>'order_id']);
    }
    public function getParent(): ActiveQuery
    {
        return $this->hasOne(OrderItem::class, ['id'=>'parent_id']);
    }
    public function getChildren(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['parent_id' => 'id']);
    }

##############################################
    public static function tableName(): string
    {
        return '{{%shop_order_items}}';
    }
    public function afterFind(): void
    {
        $this->blockData = new BlockData(
            $this->getAttribute('block_id'),
            $this->getAttribute('block_name')
        );

        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('block_id', $this->blockData->id);
        $this->setAttribute('block_name', $this->blockData->name);


        return parent::beforeSave($insert);
    }
}