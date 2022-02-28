<?php

namespace backend\bootstrap;

use rent\forms\manage\Client\ClientChangeForm;
use rent\useCases\ContactService;
use Yii;
use yii\base\BootstrapInterface;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {

        $container = \Yii::$container;

        Yii::$app->settings;

        $container->set(CKEditor::class, [
            'editorOptions' => ElFinder::ckeditorOptions('elfinder'),
        ]);


//        if (!Yii::$app->user->isGuest) {
//            $user=Yii::$app->db
//                ->createCommand('SELECT * FROM users as u WHERE u.id=:user_id')
//                ->bindValue(':user_id',Yii::$app->user->id)
//                ->queryOne();
//
//            $this->updateClientSettings($user);
//        }
//
//        $container = \Yii::$container;
////        Event::on(View::class, View::EVENT_BEGIN_BODY, function() {
//////          TODO сделать проверку на пользователя. Кому какие клиенты и сайты можно смотреть
////            echo Yii::$app->params['clientId'];exit;
////            Yii::$app->params['clientId']=Yii::$app->session->get('client_id')?Yii::$app->session->get('client_id'):1;
////            Yii::$app->params['siteId']==Yii::$app->session->get('site_id')?Yii::$app->session->get('site_id'):1;
////            Yii::$app->view->params['clientChangForm'] = new ClientChangeForm(
////                Yii::$app->params['clientId'],
////                Yii::$app->params['siteId']
////            );
////        });
    }
//    private function setDefaultTimeZone($user)
//    {
//        date_default_timezone_set('UTC');
//        if ($user['timezone']) {
//            date_default_timezone_set($user['timezone']);
//        }
//        \Yii::$app->params['dateControlDisplayTimezone']=$user['timezone'];
//    }
//    private function updateClientSettings($user)
//    {
//        $default_site=$user['default_site']?:1;
//
//        /** @var Settings $settings */
////        if (!$settings=Yii::$app->session->get('settings')) {
//        if (!$settings=Settings::load()) {
//            if ($result=Yii::$app->db
//                ->createCommand('SELECT c.id as site_id, c.client_id as client_id, c.timezone as timezone FROM client_sites as c WHERE c.id=:site_id')
//                ->bindValue(':site_id',$default_site)
//                ->queryOne()) {
//
//                $settings=new Settings($result['site_id'],$result['client_id'],$result['timezone']);
//                $settings->save();
//            }
//        }
//        if ($settings->timezone) {
//            date_default_timezone_set($settings->timezone);
//        } else {
//            date_default_timezone_set('UTC');
//        }
//        \Yii::$app->params['dateControlDisplayTimezone']=date_default_timezone_get();
//
//        Yii::$app->params['siteId']=$settings->site_id;
//        Yii::$app->params['clientId']=$settings->client_id;
//        Yii::$app->params['timezone']=$settings->timezone;
//
//        Yii::$app->view->params['clientChangForm'] = new ClientChangeForm(
//            Yii::$app->params['clientId'],
//            Yii::$app->params['siteId']
//        );
//    }
}