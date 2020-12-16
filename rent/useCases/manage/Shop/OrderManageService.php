<?php

namespace rent\useCases\manage\Shop;

use rent\cart\CartItem;
use rent\entities\Shop\Order\CustomerData;
use rent\entities\Shop\Order\Item\BlockData;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Item\PeriodData;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use rent\entities\Shop\Order\Status;
use rent\entities\Shop\Service;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Tag;
use rent\forms\manage\Shop\Order\Item\BlockForm;
use rent\forms\manage\Shop\Order\Item\ItemForm;
use rent\forms\manage\Shop\Order\OrderCartForm;
use rent\forms\manage\Shop\Order\OrderEditForm;
use rent\forms\manage\Shop\Order\OrderCreateForm;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\readModels\Shop\ProductReadRepository;
use rent\repositories\Shop\OrderRepository;
use rent\entities\Shop\Order\DeliveryData;
use rent\services\export\OrderExportService;

class OrderManageService
{
    private $orders;
    private $products;
    private $export;

    public function __construct(OrderRepository $orders, ProductReadRepository $products, OrderExportService $export)
    {
        $this->orders = $orders;
        $this->products = $products;
        $this->export = $export;
    }

    public function create(OrderCreateForm $form): Order
    {
        $order = Order::create(
            $form->responsible_id,
            $form->name,
            $form->code,
            $form->date_begin,
            $form->date_end?:null,
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

    public function addPayment($id, PaymentForm $form): void
    {
        $order = $this->orders->get($id);
        $payments = $order->payments;
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
        $payments[] = $payment;
        $order->payments = $payments;
        $this->orders->save($order);

    }

    public function removePayment($id, $payment_id): void
    {
        $order = $this->orders->get($id);
        $order->removePayment($payment_id);
        $this->orders->save($order);
    }

    public function addBlock($id, $name): OrderItem
    {
        $order = $this->orders->get($id);
        $block=$order->addBlock($name);
        $this->orders->save($order);
        return $block;

    }

    public function editBlock($id, $item_id,BlockForm $form): void
    {
        $order = $this->orders->get($id);
        $order->editBlock($item_id,$form->name);
        $this->orders->save($order);
    }

    public function removeBlock($id, $block_id): void
    {
        $order = $this->orders->get($id);
        $order->removeBlock($block_id);
        $this->orders->save($order);
    }

    public function moveBlockUp($id, $block_id): void
    {
        $order = $this->orders->get($id);
        $order->moveBlockUp($block_id);
        $this->orders->save($order);
    }
    public function moveBlockDown($id, $block_id): void
    {
        $order = $this->orders->get($id);
        $order->moveBlockDown($block_id);
        $this->orders->save($order);
    }
###Service
    public function addService($id, Service $service): void
    {
        $order = $this->orders->get($id);
        $order->addService($service);
        $this->orders->save($order);
    }
###Item
    public function addItem($type_id,$parent_id,$qty=1, $product_id=null, $price=null, $name=null):void
    {
        $type_id=(int)$type_id;
        $parent=$this->orders->getItem($parent_id);
        $order=$parent->order;
        $item = new CartItem(
            $type_id,
            $qty,
            $parent,
            $price,
            null,
            $name,
            null
        );
        switch ($type_id){
            case (OrderItem::TYPE_RENT):
                $product=$this->products->find($product_id);
                $item->product=$product;
                $item->periodData=$order->getPeriod();
                $item->price=$product->priceRent;
                $item->name=$product->name;
                $order->addItem($item);
                break;
            case (OrderItem::TYPE_SALE):
                $product=$this->products->find($product_id);
                $item->product=$product;
                $item->price=$product->priceSale;
                $item->name=$product->name;
                $order->addItem($item);
                break;
            default:
                $order->addItem($item);
        }
        $order->calcService();
        $this->orders->save($order);
    }
    public function editItem($id, $item_id,ItemForm $form):void
    {
        $order=$this->orders->get($id);
        $order->editItem(
            $item_id,
            $form->name,
            $form->price,
            $form->qty,
            $form->period_qty,
            $form->is_montage,
            $form->note
        );
        $this->orders->save($order);
        $order->calcService();
        $this->orders->save($order);
    }

    public function removeItem($id,$item_id): void
    {
        $order = $this->orders->get($id);
        $order->removeItem($item_id);
        $this->orders->save($order);
    }
    public function removeItems($id,$itemIds): void
    {
        $order = $this->orders->get($id);
        foreach ($itemIds as $itemId) {
            $this->guardCanRemoveItem($itemId);
            $order->removeItem($itemId);
        }
        $this->orders->save($order);
    }
###Status
    public function changeStatus(int $id,int $status_id):void
    {
        $order = $this->orders->get($id);
        switch ($status_id){
            case 0:
                $order->makeNew(true);
                break;
            case Status::isNew($status_id):
                $order->makeNew();
                break;
            case Status::ESTIMATE:
                $order->estimate();
                break;
            case Status::CANCELLED:
                $order->cancel();
                break;
            case Status::COMPLETED:
                $order->complete();
        }
        $this->orders->save($order);
    }
###Operation
    public function addOperation($id,$operation_id,$arrQty): void
    {
        $order = $this->orders->get($id);
        $items=$order->itemsWithoutBlocks;
        foreach ($items as $item) {
            if (key_exists($item->id,$arrQty)) {
                $item->addOperation($operation_id,null,$arrQty[$item->id]);
            }
        }
        $order->itemsWithoutBlocks=$items;
        $this->orders->save($order);
    }
###Export
    public function exportOrder($id):string
    {
        $order = $this->orders->get($id);
        return $this->export->exportOrderToExcel($order);
    }
    public function exportOrders($dataProvider):string
    {
        return $this->export->exportOrdersToExcel($dataProvider);
    }
    #########################################
    protected function internalForms(): array
    {
        return ['customer'];
    }
###Guard
    public function guardCanRemoveItem($itemId):void
    {
        $item=$this->orders->getItem($itemId);
        if ($item->order->readOnly()) {
            throw new \DomainException('Удалять позицию можно только в заказе со статусом "Черновик"');
        }
    }

}