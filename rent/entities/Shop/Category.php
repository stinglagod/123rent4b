<?php

namespace rent\entities\Shop;

use paulzi\nestedsets\NestedSetsBehavior;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\behaviors\MetaBehavior;
use rent\entities\Meta;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\queries\CategoryQuery;
use yii\data\DataProviderInterface;
use yii\db\ActiveRecord;
use rent\entities\Client\Client;
use yii\db\ActiveQuery;
use Yii;
use rent\entities\behaviors\NestedSetsTreeBehavior;
use yii\helpers\ArrayHelper;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $code
 * @property string $title
 * @property string $description
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property Meta $meta
 * @property integer $site_id
 * @property integer $on_site
 *
 * @property \rent\entities\Client\Site $site
 * @property Category[] $parents
 * @property Category[] $children
 * @property Product[] $products
 * @property Category $parent
 * @property Category $prev
 * @property Category $next
 * @mixin NestedSetsBehavior
 */
class Category extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, $code, $title, $description, Meta $meta): self
    {
        $category = new static();
        $category->name = $name;
        $category->slug = $slug;
        $category->code = $code;
        $category->title = $title;
        $category->description = $description;
        $category->meta = $meta;
        return $category;
    }
    public static function createRoot(): self
    {
        $category = new static();
        $category->name = '<Корень>';
        $category->slug = 'root';
        $category->title = null;
        $category->description =  null;
        $category->meta = '{}';
        $category->lft=1;
        $category->rgt=2;
        $category->depth=0;
        $category->meta = new Meta('','','');
        return $category;
    }
    public function edit($name, $slug, $code, $title, $description, Meta $meta): void
    {
        $this->name = $name;
        $this->slug = $slug;
        $this->code = $code;
        $this->title = $title;
        $this->description = $description;
        $this->meta = $meta;
    }

    public function isOnSite():bool
    {
        return boolval($this->on_site);
    }
    public function onSite():void
    {
        if ($this->isOnSite())
            throw new \DomainException('Category is already on Site.');

        $this->on_site=true;
    }
    public function offSite($excludeProduct_id=null):void
    {
        if (!$this->isOnSite())
            throw new \DomainException('Category is already not on Site.');

        $products=$this->products;
        $hasProductOnSite=false;
        foreach ($products as $product) {
            if (($product->isIdEqualTo($excludeProduct_id))&&($product->isOnSite())) {
                $hasProductOnSite=true;
                break;
            }
        }
        if (!$hasProductOnSite) {
            $this->on_site=false;
        }
    }
################################################
    public function getSeoTitle(): string
    {
        return $this->meta->title ?: $this->getHeadingTile();
    }

    public function getHeadingTile(): string
    {
        return $this->title ?: $this->name;
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
            ],
            NestedSetsTreeBehavior::class,
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
//        return $query;
        return $query->andWhere(['site_id' => Yii::$app->params['siteId']]);
    }

    public function getSite() :ActiveQuery
    {
        return $this->hasOne(Client::class, ['id' => 'site_id']);
    }

    public static function findBySlug(string $slug)
    {
        return static::findOne(['slug'=>$slug]);
    }

    public static function getRoot()
    {
        return self::findBySlug('root');
    }

    public function getProducts()
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');

        $ids = ArrayHelper::merge([$this->id], $this->getDescendants()->select('id')->column());
        $query->joinWith(['categoryAssignments ca'], false);
        $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        $query->groupBy('p.id');
        return $query->all();
    }
}