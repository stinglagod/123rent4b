<?php

namespace rent\helpers;

use rent\entities\Shop\Product\Movement\Action;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActionHelper
{
    public static function statusList(): array
    {
        return [
            Action::TYPE_RESERVE_SOFT => 'Мягкий резерв',
            Action::TYPE_RESERVE_HARD => 'Жесткий резерв',
            Action::TYPE_MOVE => 'Перемещение',
            Action::TYPE_RENT => 'Аренда',
            Action::TYPE_REPAIRS => 'Ремонт'
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

}