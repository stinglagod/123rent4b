<?php

namespace rent\cart;

use rent\entities\Shop\Order\Item\BlockData;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Item\PeriodData;
use rent\entities\Shop\Product\Modification;
use rent\entities\Shop\Product\Product;
use rent\helpers\OrderHelper;
use rent\helpers\ProductHelper;

class CartItem
{
    public $product;
    public $type_id;
    public $name;
    public $qty;
    public $parent;
    public $price;
    public $blockData;
    public $periodData;
    public $collect;
    public $createCustomer;

    public function __construct(int $type_id, $qty, OrderItem $parent=null, $price=null, Product $product=null, $name=null, PeriodData $periodData=null, $createCustomer=false)
    {

        if ($product) {
            $this->product=$product;
            $this->name=$product->name;
            if (empty($price)) {
                $this->price=$product->getPriceByType($type_id);
            }
        } else {
            $this->name=$name;
        }


        $this->type_id = $type_id;
        $this->price = $price;
        $this->qty = $qty;
        $this->parent = $parent;
        $this->periodData=$periodData;
        $this->createCustomer=$createCustomer;

    }

    public function getId(): string
    {
        return md5(serialize([$this->product->id]));
    }

    public function getProductId(): int
    {
        return $this->product->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }


    public function getQuantity(): int
    {
        return $this->qty;
    }

    public function getType(): int
    {
        return $this->type_id;
    }
    public function getTypeName(): string
    {
        return OrderHelper::typeOrderItemName($this->type_id);
    }

    public function getPrice(): int
    {
         return $this->product->getPriceByType($this->type_id);
    }

    public function getPrice_text(): string
    {
        return $this->product->getPriceByType_text($this->type_id);
    }

    public function getCost(): int
    {
        return $this->getPrice() * $this->qty;
    }

    public function plus($qty)
    {
        return new static($this->type_id,$this->qty + $qty,null,$this->price,$this->product);
    }

    public function changeQuantity($qty)
    {
        return new static($this->type_id,$qty,null,$this->price,$this->product);
    }
}