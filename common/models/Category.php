<?php

namespace common\models;

use common\models\behavior\NestedSetsTreeBehavior;
use Yii;
//use creocoder\nestedsets\NestedSetsBehavior;
use common\models\behavior\MyNestedSetsBehavior;

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
 * @property string $alias
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
                'class' => MyNestedSetsBehavior::className(),
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
            [['alias'], 'string', 'max' => 255],
            [['alias'], 'unique'],
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
            'alias' => Yii::t('app', 'Псевдоним'),
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
            $this->updateAlias();
            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

    }

    /**
     * Обновляем псеводним
     */
    public function updateAlias()
    {
        $this->alias=$this->getPathAlias();
//        $this->save();
        $children=$this->children()->all();
        foreach ($children as $child) {
//            $child->updateAlias();
            $child->save();
        }
//        $this->save();
    }
    /**
     * Адрес категории
     */
//    TODO: Сделать по изящнее
//https://elisdn.ru/blog/33/generaciia-url-dlia-vlojennih-kategorii-v-yii
    public function getUrl()
    {
        return '/admin/category'.$this->alias;
    }

    public static function findCategory($condition)
    {
        if (is_numeric($condition)) {
            $conditions=['id'=>(int)$condition];
        } else {
            $conditions=['alias'=>$condition];
        }

        if ($model=Category::find()->where($conditions)->one()) {
            return $model;
        } else {
            return false;
        }
    }

}
