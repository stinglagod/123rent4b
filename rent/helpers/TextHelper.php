<?php

namespace rent\helpers;

use rent\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TextHelper
{
    public static function formatPrice(float $price,string $currency=''): string
    {
        if (empty($price))
            return '';
        return number_format($price,2,',',' ') .' '. $currency;
    }
}