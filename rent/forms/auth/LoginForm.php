<?php
namespace rent\forms\auth;

use yii\base\Model;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['email', 'email'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'email'=>'Email',
            'password'=>'Пароль',
            'rememberMe'=>'Запомнить',
        ];
    }
}