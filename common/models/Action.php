<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%action}}".
 *
 * @property int $id
 * @property string $name
 * @property int $sing
 * @property string $type
 * @property string $shortName
 * @property instringt $sequence
 * @property int $order
 *
 * @property Movement[] $movements
 */
class Action extends \yii\db\ActiveRecord
{
    const SOFTRENT=1;
    const UNSOFTRENT=2;
    const HARDRENT=3;
    const UNHARDRENT=4;
    const ISSUE=5;
    const RETURN=6;
    const TOREPAIR=7;
    const FROMREPAIR=8;
    const PRIHOD=9;
    const UHOD=10;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%action}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sing','order'], 'integer'],
            [['type','sequence'], 'string'],
            [['name','shortName'], 'string', 'max' => 100],
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
            'sing' => Yii::t('app', 'Sing'),
            'type' => Yii::t('app', 'Type'),
            'shortName' => Yii::t('app', 'Короткое имя'),
            'sequence' => Yii::t('app', 'Последовательность'),
            'order' => Yii::t('app', 'Порядок'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMovements()
    {
        return $this->hasMany(Movement::className(), ['action_id' => 'id']);
    }
}
