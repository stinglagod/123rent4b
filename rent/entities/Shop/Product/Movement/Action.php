<?php

namespace rent\entities\Shop\Product\Movement;

use yii\db\ActiveRecord;

class Action extends ActiveRecord
{
    const TYPE_RESERVE_SOFT = 1;
    const TYPE_RESERVE_HARD = 2;
    const TYPE_MOVE = 3;
    const TYPE_RENT = 4;
    const TYPE_REPAIRS = 5;


// TODO: сделаю позже, а может и не сделаю. Пока особой надобности в редактировании этой таблицы нет.
    #########
    public static function tableName(): string
    {
        return '{{%shop_actions}}';
    }
}