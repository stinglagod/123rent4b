<?php

namespace rent\readModels\Shop;

use rent\entities\Shop\Order\Item\ItemBlock;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use rent\entities\Shop\Product\Movement\Movement;
use rent\entities\Shop\Service;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class OrderReadRepository
{
    public function find($id): ?Order
    {
        return Order::findOne($id);
    }

    public function getAllPayments(Order $order): DataProviderInterface
    {
        $query = Payment::find()->andWhere(['order_id'=>$order->id]);
        return $this->getProvider($query);
    }
    public function getAllMovements(Order $order): DataProviderInterface
    {
        $query = Movement::find()->joinWith('orderItem as item')->andWhere(['item.order_id'=>$order->id])->with('orderItem');
        return $this->getProvider($query);
    }

    public function getAllItemBlocks(): DataProviderInterface
    {
        $query = ItemBlock::find();
        return $this->getProvider($query);
    }
    public function getAllServices(): DataProviderInterface
    {
        $query = Service::find();
        return $this->getProvider($query);
    }
    public function getItemFromBlock(OrderItem $block): DataProviderInterface
    {
        return $this->getProvider($block->getChildren());
    }
    public function getBlockFromOrderArray(Order $order): array
    {
        $out =[];
        foreach ($order->blocks as $block) {
            $out[]=['id'=>$block->block_id,'name'=>$block->block_name];
        }
        return $out;
    }

    /**
     * под вопросом, правильно ли это делать тут
     * @param OrderItem $block
     * @return array
     */
    public static function getCollectFromBlockArray(OrderItem $block): array
    {
        $out =[];
        foreach ($block->collects as $collect) {
            $out[]=['id'=>$collect->id,'name'=>$collect->name];
        }
        return $out;
    }

    public static function getActualOrder():Order
    {

    }

########################################################################

    public static function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [15, 100],
            ]
        ]);
    }
}