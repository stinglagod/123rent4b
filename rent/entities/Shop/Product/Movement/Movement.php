<?php

namespace rent\entities\Shop\Product\Movement;

use yii\db\ActiveRecord;

class Movement extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%shop_movements}}';
    }

    public static function create($name, $type, $required, $default, array $variants, $sort): self
    {

    }
}