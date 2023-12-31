<?php

namespace rent\entities\Shop;

use rent\entities\Client\Client;
use yii\db\ActiveRecord;
use rent\entities\Client\Site;
use yii\db\ActiveQuery;
use rent\entities\behaviors\ClientBehavior;
use Yii;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $site_id
 * @property integer $client_id
 *
 * @property Site $site
 * @property Client $client
 */
class Tag extends ActiveRecord
{
    public static function create($name, $slug): self
    {
        $tag = new static();
        $tag->name = $name;
        $tag->slug = $slug;
        return $tag;
    }

    public function edit($name, $slug): void
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    public static function tableName(): string
    {
        return '{{%shop_tags}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
        ];
    }

    public function getSite() :ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }

    public function getClient() :ActiveQuery
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    public static function find()
    {
        return parent::find()->where(['client_id' => Yii::$app->settings->getClientId()]);
    }
    public function attributeLabels()
    {
        return[

            'id' => Yii::t('app','Id'),
            'name'=>Yii::t('app','Название'),
            'slug'=>Yii::t('app','Транслитерация'),
        ];
}}