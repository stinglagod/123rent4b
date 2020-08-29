<?php

namespace rent\helpers;

use rent\entities\Client\Client;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;

class AppHelper
{
    public static function isSite():bool
    {
        return Yii::$app->id=='app-frontend';
    }
}