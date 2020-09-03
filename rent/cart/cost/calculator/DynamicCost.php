<?php

namespace rent\cart\cost\calculator;

use rent\cart\cost\Cost;
use rent\cart\cost\Discount as CartDiscount;
//use rent\entities\Shop\Discount as DiscountEntity;

class DynamicCost implements CalculatorInterface
{
    private $next;

    public function __construct(CalculatorInterface $next)
    {
        $this->next = $next;
    }

    public function getCost(array $items): Cost
    {
//        /** @var DiscountEntity[] $discounts */
//        $discounts = DiscountEntity::find()->active()->orderBy('sort')->all();

        $cost = $this->next->getCost($items);

//        foreach ($discounts as $discount) {
//            if ($discount->isEnabled()) {
//                $new = new CartDiscount($cost->getOrigin() * $discount->percent / 100, $discount->name);
//                $cost = $cost->withDiscount($new);
//            }
//        }

        return $cost;
    }
}