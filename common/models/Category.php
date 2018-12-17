<?php

namespace common\models;

use common\models\behavior\NestedSetsTreeBehavior;
use Yii;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "{{%category}".
 *
 * @property int $id
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property string $name
 * @property int $client_id
 *
 * @property Client $client
 *
 * @mixin NestedSetsBehavior
 */
class Category extends \yii\db\ActiveRecord
{
    public $sub;
    public $root;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
            'htmlTree'=>[
                'class' => NestedSetsTreeBehavior::class,
                'multiple_tree'=>true
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'client_id'], 'required'],
            [['lft', 'rgt', 'depth','tree' ], 'safe'],
            [['tree', 'lft', 'rgt', 'depth', 'client_id','sub'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tree' => Yii::t('app', 'Tree'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'depth' => Yii::t('app', 'Depth'),
            'name' => Yii::t('app', 'Name'),
            'client_id' => Yii::t('app', 'Client ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    public static function getRoot()
    {

        if ($root=Category::find()->andWhere(['depth'=>0, 'client_id'=>User::findOne(Yii::$app->user->id)->client_id])->one()) {
            return $root;
        } else {
            $root = new Category(['name' => 'Корень','client_id'=>User::findOne(Yii::$app->user->id)->client_id]);
            $root->makeRoot();
            return $root;
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->client_id=User::findOne(Yii::$app->user->id)->client_id;
            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }
}
