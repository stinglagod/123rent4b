<?php

namespace rent\entities\Shop\Category;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use paulzi\nestedsets\NestedSetsBehavior;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\behaviors\MetaBehavior;
use rent\entities\Client\Site;
use rent\entities\Meta;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\queries\CategoryQuery;
use rent\helpers\AppHelper;
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
 * @property integer $client_id
 * @property integer $show_without_goods
 *
 * @property \rent\entities\Client\Site $site
 * @property Category[] $parents
 * @property Category[] $children
 * @property Product[] $products
 * @property Category $parent
 * @property Category $prev
 * @property Category $next
 * @property Client $client
 * @mixin NestedSetsBehavior
 * @property SiteAssignment[] $siteAssignments
 * @property Site[] $sites
 */
class Category extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, $code, $title, $description, Meta $meta,$showWithoutGoods,$onSite): self
    {
        $category = new static();
        $category->name = $name;
        $category->slug = trim($slug);
        $category->code = $code;
        $category->title = $title;
        $category->description = $description;
        $category->meta = $meta;
        $category->show_without_goods = $showWithoutGoods;
        $category->on_site = $onSite;
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
    public function edit($name, $slug, $code, $title, $description, Meta $meta,$showWithoutGoods,$onSite): void
    {
        $this->name = $name;
        $this->slug = trim($slug);
        $this->code = $code;
        $this->title = $title;
        $this->description = $description;
        $this->meta = $meta;
        $this->show_without_goods = $showWithoutGoods;
        $this->on_site = $onSite;
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
        if ($this->hasParent()) {
            $this->onSiteParent($this->parent);
        }

    }
    public function offSite($excludeProduct_id=null):void
    {
        if (!$this->isOnSite())
            throw new \DomainException('Category is already not on Site.');
    }

    public function onShowWithoutGoods()
    {
        $this->show_without_goods=1;
    }

    public function offShowWithoutGoods()
    {
        $this->show_without_goods=0;
    }

    private function hasProductsOnSite($excludeProduct_id=null):bool
    {
        foreach ($this->products as $product) {
            if ((!$product->isIdEqualTo($excludeProduct_id))&&($product->isOnSite())) {
                return true;
            }
        }
        return false;
    }
    private function onSiteParent(self $parent):void
    {
        if ($parent->isRoot())
            return;

        if (!$parent->isOnSite())
            $parent->onSite();

        if ($this->hasParent())
            $this->onSiteParent($parent->parent);
    }
    private function offSiteParent(self $parent):void
    {
        if ($parent->isRoot())
            return;
        if ($parent->isOnSite())
            $parent->offSite();

        if ($this->hasParent())
            $this->offSiteParent($parent->parent);
    }
    public function hasParent():bool
    {
        return (($this->parent) and (!$this->parent->isRoot()));
    }

    // Sites

    public function assignSite($id): void
    {
        $assignments = $this->siteAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForSite($id)) {
                return;
            }
        }
        $assignments[] = SiteAssignment::create($id);
        $this->siteAssignments = $assignments;
    }

    public function revokeSite($id): void
    {
        $assignments = $this->siteAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForSite($id)) {
                unset($assignments[$i]);
                $this->siteAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeSites(): void
    {
        $this->siteAssignments = [];
    }

    /**
     *  Проходит по всему каталогу и проверяет есть ли у категории товар. Если есть тогда категорию публикуем на сайте
     */
    public static function updateAvailabilityGoods()
    {
        $categories=self::find()->all();
        /** @var Category $category */
        foreach ($categories as $category) {
            if (!$category->show_without_goods) {
                if ($category->products) {
                    $categorySites=$category->sites;
                    foreach ($category->products as $product) {
                        $categorySites=array_merge($categorySites,$product->sites);
                    }
                    $category->sites=$categorySites;
                    $category->save();
                } else {
                    $category->sites=[];
                    $category->save();
                }
            }
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
                'treeAttribute'=>'client_id'
            ],
            NestedSetsTreeBehavior::class,
            'SaveRelationsBehavior'=>
                [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'siteAssignments',
                        'sites',
                    ],
                ],
        ];
    }

    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    public static function findOneForce($id)
    {
        return self::find(true)->where(['id'=>$id])->one();
    }
    public static function find($all=false): CategoryQuery
    {
//        dump(Yii::$app->settings->getClientId());
        $query=new CategoryQuery(static::class);
        if ($all) {
            return $query;
        } else {
//            if (Yii::$app->settings->site) {
//                $query->joinWith(['siteAssignments sa'], false);
//                $query->andWhere(['OR',
//                    ['slug'=>'root'],
//                    ['sa.site_id' => Yii::$app->settings->site->id]]
//                );
//                $query->groupBy('id');
//                if (AppHelper::isSite()) {
//                    $query->andWhere(['OR',
//                        ['slug'=>'root'],
//                        ['on_site' => 1]
//                    ]);
//                }
//            }
            return $query->andWhere(['client_id' => Yii::$app->settings->getClientId()]);
        }
        return $query->andWhere(['client_id' => Yii::$app->settings->getClientId()]);
    }

    public function getClient() :ActiveQuery
    {
        return $this->hasOne(Client::class, ['id' => 'site_id']);
    }

    public function getSite(): ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }

    public function getSiteAssignments(): ActiveQuery
    {
        return $this->hasMany(SiteAssignment::class, ['category_id' => 'id']);
    }

    public function getSites(): ActiveQuery
    {
        return $this->hasMany(Site::class, ['id' => 'site_id'])->via('siteAssignments');
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

    public function getMetaDescription():string
    {
        if ($this->meta->description) {
            return $this->meta->description;
        } else {
            return $this->name . ' ' . $this->description;
        }
    }
}