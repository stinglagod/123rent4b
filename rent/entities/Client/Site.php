<?php

namespace rent\entities\Client;

use rent\entities\Client\Client;
use rent\entities\Shop\Category;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\Meta;

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
 * @property Category[] $categories
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
        // добавляем корень категории
        $categories=$site->categories;
        $category=Category::create(
            '<Корень>',
            'root',
            null,
            null,
            new Meta('','','')
        );
        $category->makeRoot();
        $categories[]=$category;
        $site->categories=$categories;
//        var_dump($category);exit;
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
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['categories'],
            ],
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

    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['site_id' => 'id']);
    }

    public function beforeDelete(): bool
    {
        if (parent::beforeDelete()) {
            $this->deleteCategories();
            return true;
        }
        return false;
    }
    public function deleteCategories():void
    {
        var_dump($this->categories);exit;
        $this->categories[0]->getRoot()->one()->deleteWithChildren();
    }

//    private function createRootCategory()
//    {
////        if (!Category::getRoot()) {
//            $category=Category::create(
//                '<Корень>',
//                'root1',
//                null,
//                null,
//                new Meta('','','')
//            );
//
//            $category->site_id=$this->id;
//            $category->makeRoot();
//
//            var_dump($category);exit;
//            $category->save();
//
////        }
//    }
//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
//        $this->createRootCategory();
//    }
}
