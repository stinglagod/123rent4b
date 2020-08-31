<?php

namespace rent\repositories\Shop;

use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Order;
use rent\repositories\NotFoundException;

class OrderRepository
{
    public function get($id): Order
    {
        if (!$order = Order::findOne($id)) {
            throw new NotFoundException('Order is not found.');
        }
        return $order;
    }

    public function save(Order $order): void
    {
        if (!$order->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Order $order): void
    {
        if (!$order->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function getBlock($id): OrderItem
    {
        if (!$block = OrderItem::find()->where(['block_id'=>$id])->one()) {
            throw new NotFoundException('Block is not found.');
        }
        return $block;
    }

    public function getItem($id):OrderItem
    {
        if (!$orderItem = OrderItem::findOne($id)) {
            throw new NotFoundException('OrderItem is not found.');
        }
        return $orderItem;
    }
}