<?php

namespace rent\entities\Shop\Product\Movement;

use yii\db\ActiveRecord;

class Balance extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%shop_balance}}';
    }
}