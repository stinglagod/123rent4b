<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\protect\MyActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property int $client_id
 * @property int $avatar_id
 *
 * @property Client $client
 * @property File $avatar
 */
class User extends MyActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['name','surname'], 'string', 'min' => 2, 'max' => 255],
            [['name','surname'], 'required'],
            ['patronymic', 'string', 'max' => 255],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Адрес электронной почты уже используется'],
            ['telephone', 'match', 'pattern' => '/^\+7\([0-9]{3}\)[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'message' => ' Не верный формат телефона. Используйте +7(999)999-99-99' ],
            [['client_id','avatar_id'], 'integer'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'id']],
            [['avatar_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['avatar_id' => 'id']],
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
            'client_id' => Yii::t('app', 'Клиент'),
            'avatar_id' => Yii::t('app', 'Аватар'),
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
    public function getAvatarUrl($size=null)
    {
        if ($this->avatar_id) {
            return $this->avatar->getUrl($size);
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
