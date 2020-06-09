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

        Event::on(View::class, View::EVENT_BEGIN_BODY, function() {
            Yii::$app->view->params['clientChangForm'] = new ClientChangeForm(
                Yii::$app->session->get('client_id'),
                Yii::$app->session->get('site_id')
            );
        });
    }
}