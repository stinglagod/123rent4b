<?php

namespace rent\entities\Shop;

use paulzi\nestedsets\NestedSetsBehavior;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\behaviors\MetaBehavior;
use rent\entities\Meta;
use rent\entities\Shop\queries\CategoryQuery;
use yii\db\ActiveRecord;
use rent\entities\Client\Client;
use yii\db\ActiveQuery;
use Yii;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property Meta $meta
 * @property integer $site_id
 *
 * @property \rent\entities\Client\Site $site
 * @property Category $parent
 * @property Category $prev
 * @property Category $next
 * @mixin NestedSetsBehavior
 */
class Category extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, $title, $description, Meta $meta): self
    {
        $category = new static();
        $category->name = $name;
        $category->slug = $slug;
        $category->title = $title;
        $category->description = $description;
        $category->meta = $meta;
        return $category;
    }

    public function edit($name, $slug, $title, $description, Meta $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->title = $title;
        $this->description = $description;
        $this->meta = $meta;
    }

    public static function tableName(): string
    {
        return '{{%shop_categories}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
            MetaBehavior::class,
            [
                'class'=>NestedSetsBehavior::class,
                'treeAttribute'=>'site_id'
            ]
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): CategoryQuery
    {
        $query=new CategoryQuery(static::class);
        return $query->andWhere(['site_id' => Yii::$app->params['siteId']]);
    }

    public function getSite() :ActiveQuery
    {
        return $this->hasOne(Client::class, ['id' => 'site_id']);
    }

    public static function findBySlug(string $slug)
    {
        static::findOne(['slug'=>$slug]);
    }
}