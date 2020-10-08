<?php

namespace rent\useCases\Shop;

use rent\cart\Cart;
use rent\cart\CartItem;
use rent\entities\Shop\Order\CustomerData;
use rent\entities\Shop\Order\DeliveryData;
use rent\entities\Shop\Order\Item\PeriodData;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\forms\Shop\Order\OrderForm;
use rent\helpers\OrderHelper;
use rent\repositories\Shop\DeliveryMethodRepository;
use rent\repositories\Shop\OrderRepository;
use rent\repositories\Shop\ProductRepository;
use rent\repositories\UserRepository;
use rent\services\TransactionManager;

class OrderService
{
    private $cart;
    private $orders;
    private $products;
    private $users;
//    private $deliveryMethods;
    private $transaction;

    public function __construct(
        Cart $cart,
        OrderRepository $orders,
        ProductRepository $products,
        UserRepository $users,
//        DeliveryMethodRepository $deliveryMethods,
        TransactionManager $transaction
    )
    {
        $this->cart = $cart;
        $this->orders = $orders;
        $this->products = $products;
        $this->users = $users;
//        $this->deliveryMethods = $deliveryMethods;
        $this->transaction = $transaction;
    }

    public function checkout($userId, OrderForm $form): Order
    {
        $user = $this->users->get($userId);

        $products = [];

        $items = array_map(function (CartItem $item) use (&$form) {
            if ($item->isRent()) {
                if (empty($form->date_end)) {
                    throw new \DomainException('Для аренды обязательно для заполнения дата окончания');
                }
                $item->periodData=new PeriodData(OrderHelper::countDaysBetweenDates($form->date_begin,$form->date_end));
            }
            return OrderItem::create(
                $item
            );
        }, $this->cart->getItems());

        $order = Order::createFromSite(
            $user->id,
            $form->date_begin,
            (int)$form->date_end,
            new CustomerData(
                $form->customer->phone,
                $form->customer->name,
                ''
            ),
            new DeliveryData(''),
            $items,
            $this->cart->getCost()->getTotal(),
            $form->note,
            true
        );



        $this->transaction->wrap(function () use ($order, $products) {
            $this->orders->save($order);
            $this->cart->clear();
        });

        return $order;
    }
}