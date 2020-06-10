<?php

namespace backend\bootstrap;

use rent\forms\manage\Client\ClientChangeForm;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\View;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;


        Yii::$app->params['clientId']=Yii::$app->session->get('client_id')?Yii::$app->session->get('client_id'):1;
        Yii::$app->params['siteId']=Yii::$app->session->get('site_id')?Yii::$app->session->get('site_id'):1;
        Yii::$app->view->params['clientChangForm'] = new ClientChangeForm(
            Yii::$app->params['clientId'],
            Yii::$app->params['siteId']
        );
//        echo Yii::$app->params['siteId'];exit;

//        Event::on(View::class, View::EVENT_BEGIN_BODY, function() {
////          TODO сделать проверку на пользователя. Кому какие клиенты и сайты можно смотреть
//            echo Yii::$app->params['clientId'];exit;
//            Yii::$app->params['clientId']=Yii::$app->session->get('client_id')?Yii::$app->session->get('client_id'):1;
//            Yii::$app->params['siteId']==Yii::$app->session->get('site_id')?Yii::$app->session->get('site_id'):1;
//            Yii::$app->view->params['clientChangForm'] = new ClientChangeForm(
//                Yii::$app->params['clientId'],
//                Yii::$app->params['siteId']
//            );
//        });
    }
}