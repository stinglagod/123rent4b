<?php

namespace rent\repositories\Shop;

use rent\entities\Shop\Order\Order;
use rent\repositories\NotFoundException;

class OrderRepository
{
    public function get($id): Order
    {
        if (!$tag = Order::findOne($id)) {
            throw new NotFoundException('Order is not found.');
        }
        return $tag;
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
}