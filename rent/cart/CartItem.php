<?php

namespace rent\cart;

use rent\entities\Shop\Order\Item\BlockData;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Item\PeriodData;
use rent\entities\Shop\Product\Modification;
use rent\entities\Shop\Product\Product;

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

    public function __construct($type_id, $qty, OrderItem $parent, $price, Product $product=null, $name=null, PeriodData $periodData=null, $createCustomer=false)
    {
//        if (!$product->canBeCheckout($modificationId, $quantity)) {
//            throw new \DomainException('Quantity is too big.');
//        }
//        if ((empty($product)) and (empty($name))){
//            throw new \DomainException('Product and name is empty');
//        }

        if ($product) {
            $this->product=$product;
            $this->name=$product->name;
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
        return md5(serialize([$this->product->id, $this->modificationId]));
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
        return $this->quantity;
    }

//    public function getPrice(): int
//    {
//         return $this->product->price_new;
//    }
//
//    public function getWeight(): int
//    {
//        return $this->product->weight * $this->quantity;
//    }
//
//    public function getCost(): int
//    {
//        return $this->getPrice() * $this->quantity;
//    }
//
//    public function plus($quantity)
//    {
//        return new static($this->product, $this->modificationId, $this->quantity + $quantity);
//    }
//
//    public function changeQuantity($quantity)
//    {
//        return new static($this->product, $this->modificationId, $quantity);
//    }
}