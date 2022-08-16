<?php


namespace rent\repositories\Shop;


use rent\entities\Shop\Order\Payment;
use rent\repositories\NotFoundException;

class PaymentRepository
{
    public function get($id): Payment
    {
        if (!$brand = Payment::findOne($id)) {
            throw new NotFoundException('Payment is not found.');
        }
        return $brand;
    }

    public function save(Payment $entity): void
    {
        if (!$entity->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Payment $entity): void
    {
        if (!$entity->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}