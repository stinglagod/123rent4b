<?php

namespace rent\forms\manage\User;

use rent\access\Rbac;
use rent\entities\Client\Client;
use rent\entities\User\User;
use rent\helpers\UserHelper;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use rent\entities\Client\Site;
use yii\web\UploadedFile;

class UserInviteForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;
    public $surname;
    public $patronymic;
    public $telephone;
    public $default_site;
    public $avatar;
    public $role;
    public $default_client_id;

    public function __construct($default_client_id, $config = [])
    {
        $this->default_client_id=$default_client_id;


        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
//            ['email', 'unique', 'targetClass' => '\rent\entities\User\User', 'message' => 'Email уже используется'],

//            ['role', 'required'],
            ['role','default','value'=>Rbac::ROLE_USER],
            [['role'], 'in', 'range' => ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'name')],

        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->avatar = UploadedFile::getInstance($this, 'avatar');
            return true;
        }
        return false;
    }

    public function getSiteList()
    {
        return ArrayHelper::map(Site::find()->orderBy('name')->all(), 'id', 'name');
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    public function getClientsList()
    {
        return ArrayHelper::map(Client::find()->orderBy('name')->all(), 'id', 'name');
    }
}