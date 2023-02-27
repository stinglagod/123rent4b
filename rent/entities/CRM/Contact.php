<?php

namespace rent\entities\CRM;

use common\models\File;
use common\models\Movement;
use common\models\Order;
use common\models\Ostatok;
use common\models\Product;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\Client\Client;
use rent\entities\Client\Site\Counter;
use rent\entities\Client\Site\Footer;
use rent\entities\Client\Site\MainPage;
use rent\entities\Client\Site\ReCaptcha;
use rent\entities\Meta;
use rent\entities\Shop\Category\Category;
use rent\entities\Social;
use rent\entities\User\User;
use rent\helpers\TextHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%shop_contacts}}".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property integer $status
 * @property string $telephone
 * @property string $email
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 * @property integer $lastChangeUser_id
 * @property integer $client_id
 *
 * @property Client $client
 * @property User $author
 * @property User $lastChangeUser
 */
class Contact extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;

    const STATUS_ACTIVE = 10;
    const STATUS_NOT_ACTIVE = 15;

    public static function create(
        string $name,
        string $surname,
        string $patronymic,
        string $telephone,
        string $email,
        string $status=self::STATUS_ACTIVE,
        string $note='',
        int $client_id=null
    ):self
    {
        $entity = new static();
        $entity->name=$name;
        $entity->surname=$surname;
        $entity->patronymic=$patronymic;
        $entity->telephone=$telephone;
        $entity->email=$email;
        $entity->status=$status;
        $entity->note=$note;

        if ((Yii::$app->user->can('super_admin'))and($client_id)) {
            $entity->client_id=$client_id;
        } else {
            if (Yii::$app->settings->client) {
                $entity->client_id=Yii::$app->settings->client->id;
            }
        }

        return $entity;
    }


    public function edit(
        string $name,
        string $surname,
        string $patronymic,
        string $telephone,
        string $email,
        string $status,
        string $note
    ):void
    {
        $this->name=$name;
        $this->surname=$surname;
        $this->patronymic=$patronymic;
        $this->telephone=$telephone;
        $this->email=$email;
        $this->status=$status;
        $this->note=$note;
    }

    public function getClient() :ActiveQuery
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    public function getAuthor() :ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }
    public function getLastChangeUser() :ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'lastChangeUser_id']);
    }

    public function getShortName():string
    {
        return $this->name . ' ' . $this->surname;
    }

    public static function getResponsibleList():?array
    {
        return ArrayHelper::map(Contact::find()->orderBy('name')->all(), 'id', function (Contact $entity){
            return $entity->getShortName() . '('.$entity->telephone.', '.$entity->email.')';
        });
    }

    ##########################################

    public static function tableName(): string
    {
        return '{{%crm_contacts}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
            TimestampBehavior::class,
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find($all=false)
    {
        if ($all) {
            return parent::find();
        } else {
            return parent::find()->where(['client_id' => Yii::$app->settings->client->id]);
        }
    }
}