<?php

namespace rent\useCases\Shop;

use rent\cart\Cart;
use rent\cart\CartItem;
use rent\repositories\Shop\ProductRepository;

class CartService
{
    private $cart;
    private $products;

    public function __construct(Cart $cart, ProductRepository $products)
    {
        $this->cart = $cart;
        $this->products = $products;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function add($productId, $qty,$type_id): void
    {
        $product = $this->products->get($productId);
        $this->cart->add(new CartItem($type_id, $qty,null,null,$product));
    }

    public function set($id, $quantity): void
    {
        $this->cart->set($id, $quantity);
    }

    public function remove($id): void
    {
        $this->cart->remove($id);
    }

    public function clear(): void
    {
        $this->cart->clear();
    }
}