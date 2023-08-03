<?php

namespace rent\entities\Shop;

use rent\entities\Client\Client;
use rent\entities\Client\Site;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\db\ActiveQuery;
use rent\entities\behaviors\ClientBehavior;
use Yii;

/**
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $required
 * @property string $default
 * @property array $variants
 * @property integer $sort
 * @property integer $site_id
 * @property integer $client_id
 *
 * @property \rent\entities\Client\Site $site
 * @property \rent\entities\Client\Client $client
 *
 */
class Characteristic extends ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';

    public $variants;

    public static function create($name, $type, $required, $default, array $variants, $sort): self
    {
        $object = new static();
        $object->name = $name;
        $object->type = $type;
        $object->required = $required;
        $object->default = $default;
        $object->variants = $variants;
        $object->sort = $sort;
        return $object;
    }

    public function edit($name, $type, $required, $default, array $variants, $sort): void
    {
        $this->name = $name;
        $this->type = $type;
        $this->required = $required;
        $this->default = $default;
        $this->variants = $variants;
        $this->sort = $sort;
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }

    public static function tableName(): string
    {
        return '{{%shop_characteristics}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
        ];
    }

    public function afterFind(): void
    {
        $this->variants = array_filter(Json::decode($this->getAttribute('variants_json')));
        parent::afterFind();
    }

    public function beforeSave($insert): bool
    {
        $this->setAttribute('variants_json', Json::encode(array_filter($this->variants)));
        return parent::beforeSave($insert);
    }

    public function getSite() :ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }
    public function getClient() :ActiveQuery
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    public static function find($all=false)
    {
        if ($all) {
            return parent::find();
        } else {
            return parent::find()->where(['client_id' => Yii::$app->settings->getClientId()]);
        }
    }
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Название'),
            'type' => Yii::t('app', 'Тип характеристики'),
            'sort' => Yii::t('app', 'Количество'),
            'required' => Yii::t('app', 'Обязательно'),
            'default' => Yii::t('app', 'Описание'),
            'textVariants'=>Yii::t('app','Описание')
        ];
    }
}