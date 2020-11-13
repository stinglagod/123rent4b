<?php
namespace rent\forms\auth;

use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $password;
    public $password_repeat;

    public $reCaptcha;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['surname', 'trim'],
            ['surname', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\rent\entities\User\User', 'message' => 'Email уже используется'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не одинаковые" ],

            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator3::class,
                'secret' => \Yii::$app->params['secretV3'], // unnecessary if reСaptcha is already configured
                'threshold' => 0.5,
                'action' => 'signup',
                'when' => function() {return (\Yii::$app->params['siteKeyV3'] and YII_ENV_PROD);}
            ],

        ];
    }
}
