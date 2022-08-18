<?php

namespace rent\helpers;

use rent\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TextHelper
{
    public static function formatPrice(float $price): string
    {
        return number_format($price,2,',',' ');
    }
}