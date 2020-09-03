<?php

namespace rent\cart\cost\calculator;

use rent\cart\CartItem;
use rent\cart\cost\Cost;

interface CalculatorInterface
{
    /**
     * @param CartItem[] $items
     * @return Cost
     */
    public function  getCost(array $items): Cost;
} 