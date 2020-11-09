<?php
namespace rent\controllers\auth;

use yii\web\Controller;

class SignupController extends Controller
{

      public function actions()
    {
        return [
            'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
}