<?php

namespace frontend\bootstrap;

use rent\entities\Client\Site;
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

        if ($site_id=Yii::$app->db
            ->createCommand('SELECT id FROM client_sites WHERE domain=:domain')
            ->bindParam(':domain',$_SERVER['HTTP_HOST'])
            ->queryScalar())
            $app->params['siteDomain']=$_SERVER['HTTP_HOST'];
            $app->params['siteId']=$site_id;


        $container = \Yii::$container;

    }
}