<?php

namespace rent\entities\Client;

use rent\entities\Client\Client;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%client_sites}}".
 *
 * @property int $id
 * @property int $client_id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property int $status
 * @property string $telephone
 * @property string $address
 * @property string $domain
 *
 * @property Client $client
 */
class Site extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public static function create($name, $domain, $telephone, $address): self
    {
        $site = new static();
        $site->name = $name;
        $site->domain = $domain;
        $site->telephone = $telephone;
        $site->address = $address;
        return $site;
    }

    public function edit($name, $domain, $telephone, $address): void
    {
        $this->name = $name;
        $this->domain = $domain;
        $this->telephone = $telephone;
        $this->address = $address;
    }

    public function isIdEqualTo($id)
    {
        return $this->id == $id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%client_sites}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    public static function findByDomain($domain)
    {
        return static::findOne(['domain' => $domain]);
    }
}
