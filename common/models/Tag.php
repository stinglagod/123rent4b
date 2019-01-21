<?php

namespace common\models;

use common\models\protect\MyActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%tag}}".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property int $autor_id
 * @property int $lastChangeUser_id
 * @property int $client_id
 *
 * @property User $lastChangeUser
 * @property User $autor
 * @property Client $client
 */
class Tag extends MyActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tag}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['autor_id', 'lastChangeUser_id', 'client_id'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['name'], 'unique'],
            [['lastChangeUser_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['lastChangeUser_id' => 'id']],
            [['autor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['autor_id' => 'id']],
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
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'autor_id' => Yii::t('app', 'Autor ID'),
            'lastChangeUser_id' => Yii::t('app', 'Last Change User ID'),
            'client_id' => Yii::t('app', 'Client ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastChangeUser()
    {
        return $this->hasOne(User::className(), ['id' => 'lastChangeUser_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutor()
    {
        return $this->hasOne(User::className(), ['id' => 'autor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    /**
     * Ищем тег по имени. Если нет, тогда создаем
     */
    public static function findOrCreateTag($name)
    {
        if (!($tag=self::find()->where(['client_id'=>User::findOne(\Yii::$app->user->id)->client_id])->where(['name'=>$name])->one())) {
            $tag= new Tag();
            $tag->name=$name;
            $tag->save();
        }
        return $tag;
    }

    /**
     * Выводим все теги в массив
     */
    public static function getAllTags()
    {
        $tags=self::find()->where(['client_id'=>User::findOne(\Yii::$app->user->id)->client_id])->select('name')->column();
        $newTags=array();
        foreach ( $tags as $key => $value) {
//            $key=$value;
            $newTags[$value]=$value;
        }
//        $newTags=$tags;
        return $newTags;
//        return self::find()->where(['client_id'=>User::findOne(\Yii::$app->user->id)->client_id])->select('name')->column();
    }
}
