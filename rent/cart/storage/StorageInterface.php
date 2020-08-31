<?php

namespace rent\cart\storage;

use rent\cart\CartItem;

interface StorageInterface
{
    /**
     * @return CartItem[]
     */
    public function load(): array;

    /**
     * @param CartItem[] $items
     */
    public function save(array $items): void;
}