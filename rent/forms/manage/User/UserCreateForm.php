<?php

namespace rent\forms\manage\User;

use rent\entities\User\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserCreateForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $role;

    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
            [['name', 'email'], 'string', 'max' => 100],
            ['password', 'string', 'min' => 6],
        ];
    }
    public function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}