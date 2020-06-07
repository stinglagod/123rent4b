<?php
/**
 * Created by PhpStorm.
 * User: Aleksey
 * Date: 27.05.2020
 * Time: 14:15
 */

namespace common\bootstrap;

//use rent\services\ContactService;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });
        //setSingleton - класс создается один раз
//        $container->setSingleton(ContactService::class, [], [
//            $app->params['adminEmail']
//        ]);
    }

}