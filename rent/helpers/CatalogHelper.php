<?php

namespace rent\helpers;

use rent\entities\Client\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;

class CatalogHelper
{
    public static function getUrl():string
    {
        if (preg_match('#^/admin/shop/order/catalog#is', Yii::$app->request->url, $matches)) {
            return 'shop/order';
        } else {
            return '';
        }
    }
    public static function getNameLayout():string
    {
        if (preg_match('#([\w+]*)\/#', Yii::$app->layout, $matches)) {
            return $matches[1];
        } else {
            return '';
        }
    }
}