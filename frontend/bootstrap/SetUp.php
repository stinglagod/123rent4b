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

//        if (!$result=Yii::$app->db
//            ->createCommand('SELECT * FROM client_sites WHERE domain=:domain')
//            ->bindParam(':domain',$_SERVER['HTTP_HOST'])
//            ->queryOne()) {
//
//            $result=Yii::$app->db
//                ->createCommand('SELECT * FROM client_sites WHERE id=1')
//                ->queryOne();
//        }
//
//        $app->params['siteDomain']=$_SERVER['HTTP_HOST'];
//        $app->params['siteId']=$result['id'];
//        $app->params['telephone']=$result['telephone'];
//        $app->params['email']=$result['email'];
//        $app->params['address']=$result['address'];
//        $app->params['social']=new Social(
//            $result['urlInstagram'],
//            $result['urlTwitter'],
//            $result['urlFacebook'],
//            $result['urlGooglePlus'],
//            $result['urlVk'],
//            $result['urlOk']
//        );

        if (!$result=Site::findByDomain($_SERVER['HTTP_HOST'])) {
            $result=Site::findOne(1);
        }

        $app->params['siteDomain']=$_SERVER['HTTP_HOST'];
        $app->params['siteId']=$result->id;
        $app->params['telephone']=$result->telephone;
        $app->params['email']=$result->email;
        $app->params['address']=$result->address;
        $app->params['social']=new Social(
            $result->urlInstagram,
            $result->urlTwitter,
            $result->urlFacebook,
            $result->urlGooglePlus,
            $result->urlVk,
            $result->urlOk
        );
        $app->params['mainPage']=$result->mainPage;





        $container = \Yii::$container;

    }
}