<?php

namespace rent\forms\manage\User;

use rent\entities\Client\Site;
use rent\entities\User\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class UserEditForm extends Model
{
    public $username;
    public $email;
    public $name;
    public $surname;
    public $patronymic;
    public $telephone;
    public $default_site;
    public $avatar;
    public $role;


    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->name = $user->name;
        $this->surname = $user->surname;
        $this->patronymic = $user->patronymic;
        $this->telephone = $user->telephone;
        $this->default_site = $user->default_site;
        $this->role = $user->role;
        $this->_user = $user;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email'], 'required'],
            ['email', 'email'],
            [['email','name','surname','patronymic','telephone'], 'string', 'max' => 255],
            [['default_site'],'integer'],
            [['default_site'], 'exist', 'skipOnError' => true, 'targetClass' => Site::class, 'targetAttribute' => ['default_site' => 'id']],
            [['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
            [['avatar'], 'image'],
            [['role'], 'each','rule'=>['in', 'range' =>User::getAllRoles()]],
        ];
    }

    public function getSiteList()
    {
        return ArrayHelper::map(Site::find()->orderBy('name')->all(), 'id', 'name');
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->avatar = UploadedFile::getInstance($this, 'avatar');
            return true;
        }
        return false;
    }
}