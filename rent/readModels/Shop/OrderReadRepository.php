<?php

namespace rent\readModels\Shop;

use rent\entities\Shop\Order\Item\ItemBlock;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;

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

    public function getAllItemBlocks(): DataProviderInterface
    {
        $query = ItemBlock::find();
        return $this->getProvider($query);
    }
########################################################################

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSizeLimit' => [15, 100],
            ]
        ]);
    }
}