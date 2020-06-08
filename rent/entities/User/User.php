<?php
namespace rent\entities\User;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\Client\Client;
use common\models\File;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_confirm_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property int $client_id
 * @property int $avatar_id
 * @property integer $name
 * @property integer $surname
 * @property integer $patronymic
 *
 * @property Client $client
 * @property File $avatar
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_WAIT = 9;
    const STATUS_ACTIVE = 10;


    public static function create(string $name, string $email, string $password): self
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        return $user;
    }

    public function edit(string $name, string $email): void
    {
        $this->name = $name;
        $this->email = $email;
    }


    public static function requestSignup(string $name,string $surname,string $email, string $password): self
    {
        $user = new static();
        $user->name=$name;
        $user->surname=$surname;
        $user->email=$email;
        $user->setPassword($password);
        $user->created_at=date('Y-m-d H:i:s');
        $user->status=self::STATUS_WAIT;
        $user->email_confirm_token = Yii::$app->security->generateRandomString();
        $user->generateAuthKey();
        return $user;
    }

    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->email_confirm_token = null;
    }

    public static function signupByNetwork($network, $identity): self
    {
        $user = new User();
        $user->created_at = date('Y-m-d H:i:s');
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->networks = [Network::create($network, $identity)];
        return $user;
    }
    /**
     * Запрос на сброс пароля. Прежде чем сбрасываем пароль, прроверяем не сброшен ли он ранее
     *
     * @throws \yii\base\Exception
     */
    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Сброс пароля уже запрошен.');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting is not requested.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function getNetworks(): ActiveQuery
    {
        return $this->hasMany(Network::className(), ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['networks'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'username' => 'Логин',
            'email' => 'Email',
            'status' => 'Статус',
            'dateCreate_from' => 'Дата с ',
            'dateCreate_to' => 'Дата до ',
            'role' => 'Роль',
            'name'=>'Имя',
            'surname'=>'Фамилия',
            'patronymic'=>'Отчество',
            'shortName'=>'Пользователь',
            'telephone'=>'Номер телефона',
            'client_id' => 'Клиент',
            'avatar_id' => 'Аватар',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    /**
     Возращаем аватар
     **/
    public function getAvatarUrl()
    {
        if ($this->avatar_id) {
            return $this->avatar->getUrl();
        } else {
//            return Yii::$app->request->baseUrl.'/img/user2-160x160.jpg';
            return Yii::$app->request->baseUrl.'/img/noavatar.jpg';
        }
    }

    /**
    Формируем Короткое имя пользователя
     **/
    public function getShortName()
    {
        return $this->name . ' ' . $this->surname;
    }
    /**
    Формируем полное имя пользователя
     **/
    public function getFullName()
    {
        return $this->name . ' ' .(!empty($this->patronymic))?$this->patronymic:''. ' ' . $this->surname;
    }
    /**
    Return user Roles
     **/
    public function getRole()
    {
        /** @var \yii\rbac\DbManager $authManager */
        $authManager = Yii::$app->get('authManager');

        $Ridentity = $authManager->getRolesByUser($this->id);

        if($Ridentity)
        {
            foreach ($Ridentity as $item)
            {
                $role[$item->description] = $item->name;
//                $role[$item->name] =$item->description ;

            }
        }
        else
        {
            $role=null;
        }
        return $role;

    }

    function getRoleArray()
    {
        return implode(', ', $this->role);
    }

    public function getRoleTypes()
    {
        /** @var \yii\rbac\DbManager $authManager */
        $roller = Yii::$app->get('authManager')->getRoles();

        foreach ($roller as $item)
        {
//            $role[$item->name] = $item->name;
            $role[$item->name] =$item->description ;

        }
        return $role;
    }

    /**
    Возращаем массив пользователей с ролью
     **/
    public static function findUserByRole($role='user')
    {
        $users=array();
        /** @var \yii\rbac\DbManager $authManager */
        $authManager = Yii::$app->get('authManager');
        foreach ($authManager->getUserIdsByRole($role) as $id) {
            $users[]=self::findOne($id);
//            $arrTmp[$id]=$user?$user->shortName:$user->email;
        }
        return $users;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
//          TODO: решить надом нам username или не надо
            $this->username=empty($this->username)?$this->email:$this->username;
//          TODO: в связи с тем, что нельзя оставлять след. поля пустыми мы их заполняем. Но надо порешать как лучше
            if (empty($this->auth_key)) {
                $this->generateAuthKey();
            }
            if (empty($this->password_hash)) {
                $this->password_hash= Yii::$app->security->generateRandomString();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAvatar()
    {
        return $this->hasOne(File::class, ['id' => 'avatar_id']);
    }

    static public function getUserArray()
    {
        $arr=[
            '-2' => "Показать все",
            '-1' => "Показать мои"
        ];
        $arr=$arr + ArrayHelper::map(User::find()->where(['<>','id', Yii::$app->user->id])->orderBy('name')->all(), 'id', 'shortName');
        return $arr;
    }
}
