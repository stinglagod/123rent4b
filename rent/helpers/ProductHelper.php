<?php

namespace rent\helpers;

use kartik\popover\PopoverX;
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
        return ArrayHelper::getValue(self::statusOnSite(), empty($onSite)?0:1);
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
### PopoverX. Описание полей
    //on_site
    public static function popoverX_onSite(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('on_site'),$options);
    }
    //code
    public static function popoverX_code(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('code'),$options);
    }
    //priceSale_new
    public static function popoverX_priceSale_new(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('priceSale_new'),$options);
    }
    //priceSale_old
    public static function popoverX_priceSale_old(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('priceSale_old'),$options);
    }
    //priceRent_new
    public static function popoverX_priceRent_new(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('priceRent_new'),$options);
    }
    //priceSale_old
    public static function popoverX_priceRent_old(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('priceRent_old'),$options);
    }

    //priceCost
    public static function popoverX_priceCost(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('priceCost'),$options);
    }
    //priceCompensation
    public static function popoverX_priceCompensation(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('priceCompensation'),$options);
    }    //priceCompensation
    public static function popoverX_description(string $name,array $options=[]):string
    {
        return self::defaultPopoverX($name,Product::getDescriptionByAttribute('description'),$options);
    }


    public static function popoverX_balanceWarehouse(string $name,array $options=[]):string
    {
        $content='Общее кол-во товаров на складе. Без учета брони и выданных по аренде товаров';
        return self::defaultPopoverX($name,$content,$options);
    }
    public static function popoverX_balanceWarehouseWithRent(string $name,array $options=[]):string
    {
        $content='Реально кол-во товаров на текущее время. С учетом брони и выданных по аренде товаров';
        return self::defaultPopoverX($name,$content,$options);
    }
###
    public static function defaultPopoverX(string $name,string $content,array $options=[]):string
    {

        return PopoverX::widget([
            'header' => 'Описание поля: '.$name,
            'placement' => PopoverX::ALIGN_RIGHT,
            'content' => $content,
            'toggleButton' => [
                'label' => '<span class="glyphicon glyphicon-question-sign"></span>',
                'style' => 'background: none; padding: 0; margin: 0; border: none; color: #31708f'
            ],
            'options' => $options
        ]);
    }


}