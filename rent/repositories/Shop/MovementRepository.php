<?php

namespace rent\repositories\Shop;

use rent\entities\Shop\Product\Movement\Movement;
use rent\entities\Shop\Product\Product;
use rent\repositories\NotFoundException;

class MovementRepository
{
    public function save(Movement $movement): void
    {
        if (!$movement->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

}