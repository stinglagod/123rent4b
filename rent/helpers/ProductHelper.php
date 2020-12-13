<?php

namespace rent\helpers;

use rent\entities\Shop\Product\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ProductHelper
{
    public static function statusList(): array
    {
        return [
            Product::STATUS_DRAFT => 'Удален',
            Product::STATUS_ACTIVE => 'Активен',
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Product::STATUS_DRAFT:
                $class = 'label label-default';
                break;
            case Product::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function statusOnSite(): array
    {
        return [
            Product::ON_SITE => 'Опубликован',
            Product::OFF_SITE => 'Не опубликован',
        ];
    }

    public static function onSiteName($onSite): string
    {
        return ArrayHelper::getValue(self::statusOnSite(), $onSite);
    }

    public static function onSiteLabel($onSite):string
    {
        if ($onSite) {
            $class = 'label label-success';
        } else {
            $class = 'label label-default';
        }

        return Html::tag('span', ProductHelper::onSiteName($onSite), [
            'class' => $class,
        ]);
    }
}