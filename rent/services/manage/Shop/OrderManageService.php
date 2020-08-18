<?php

namespace rent\services\manage\Shop;

use rent\entities\Shop\Order\CustomerData;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use rent\entities\Shop\Tag;
use rent\forms\manage\Shop\Order\OrderEditForm;
use rent\forms\manage\Shop\Order\OrderCreateForm;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\repositories\Shop\OrderRepository;
use rent\entities\Shop\Order\DeliveryData;

class OrderManageService
{
    private $orders;

    public function __construct(OrderRepository $orders)
    {
        $this->orders = $orders;

    }

    public function create(OrderCreateForm $form): Order
    {
        $order = Order::create(
            $form->responsible_id,
            $form->name,
            $form->code,
            $form->date_begin,
            $form->date_end,
            new CustomerData(
                $form->customer->phone,
                $form->customer->name,
                $form->customer->email
            ),
            new DeliveryData(
                $form->delivery->address
            ),
            [],
            0,
            ''
        );
        $this->orders->save($order);
        return $order;
    }

    public function edit($id, OrderEditForm $form): void
    {
        $order = $this->orders->get($id);
        $order->edit(
            $form->responsible_id,
            $form->name,
            $form->code,
            $form->date_begin,
            $form->date_end,
            new CustomerData(
                $form->customer->phone,
                $form->customer->name,
                $form->customer->email
            ),
            new DeliveryData(
                $form->delivery->address
            ),
            $form->note
        );

        $this->orders->save($order);
//        var_dump($order);exit;
    }

    public function remove($id): void
    {
        $order = $this->orders->get($id);
        $this->orders->remove($order);
    }

    public function addPayment($id, PaymentForm $form):void
    {
        $order = $this->orders->get($id);
        $payments=$order->payments;
        $payment = Payment::create(
            (int)$form->dateTime,
            (int)$form->type_id,
            (float)$form->sum,
            $form->responsible_id,
            $form->responsible_name,
            $form->payer_id,
            new CustomerData(
                $form->payer->name,
                $form->payer->phone,
                $form->payer->email
            ),
            $form->purpose_id,
            $form->note
        );
        $payments[]=$payment;
        $order->payments=$payments;
        $this->orders->save($order);

    }

    public function removePayment($id, $payment_id): void
    {
        $order = $this->orders->get($id);
        $order->removePayment($payment_id);
        $this->orders->save($order);
    }


    #########################################
    protected function internalForms(): array
    {
        return ['customer'];
    }
}