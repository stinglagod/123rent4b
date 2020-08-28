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
        $this->updateClientsSettings();



        $container = \Yii::$container;
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

    private function updateClientsSettings()
    {
        if (Yii::$app->session->get('site_id')) {
            Yii::$app->params['siteId']=Yii::$app->session->get('site_id');
            Yii::$app->params['clientId']=Yii::$app->session->get('client_id');
        } else {
            $site_id= 0;
            $client_id=0;
            if ($result=Yii::$app->db
                ->createCommand('SELECT c.id as site_id, c.client_id as client_id FROM users as u, client_sites as c WHERE u.id=:user_id and u.default_site=c.id')
                ->bindValue(':user_id',Yii::$app->user->id)
                ->queryOne()) {
                $site_id= $result['site_id'];
                $client_id= $result['client_id'];
            }

            Yii::$app->params['siteId']=$site_id;
            Yii::$app->params['clientId']=$client_id;
        }

        Yii::$app->view->params['clientChangForm'] = new ClientChangeForm(
            Yii::$app->params['clientId'],
            Yii::$app->params['siteId']
        );
    }
}