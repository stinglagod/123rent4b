<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $tag
 * @property string $cod
 * @property double $primeCost
 * @property double $cost
 * @property int $priceType_id
 * @property string $is_active
 * @property int $client_id
 *
 * @property Movement[] $movements
 * @property OrderProduct[] $orderProducts
 * @property Ostatok[] $ostatoks
 * @property Client $client
 * @property PriceType $priceType
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['primeCost', 'cost'], 'number'],
            [['priceType_id', 'client_id'], 'integer'],
            [['is_active'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 1024],
            [['tag'], 'string', 'max' => 512],
            [['cod'], 'string', 'max' => 20],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
            [['priceType_id'], 'exist', 'skipOnError' => true, 'targetClass' => PriceType::className(), 'targetAttribute' => ['priceType_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'tag' => Yii::t('app', 'Tag'),
            'cod' => Yii::t('app', 'Cod'),
            'primeCost' => Yii::t('app', 'Prime Cost'),
            'cost' => Yii::t('app', 'Cost'),
            'priceType_id' => Yii::t('app', 'Price Type ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'client_id' => Yii::t('app', 'Client ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovements()
    {
        return $this->hasMany(Movement::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOstatoks()
    {
        return $this->hasMany(Ostatok::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceType()
    {
        return $this->hasOne(PriceType::className(), ['id' => 'priceType_id']);
    }
}
