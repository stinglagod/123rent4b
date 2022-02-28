<?php

namespace frontend\bootstrap;

use rent\entities\Client\Site;
use rent\entities\Social;
use rent\forms\manage\Client\ClientChangeForm;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Application;
use yii\web\Controller;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        Yii::$app->settings;
    }
}