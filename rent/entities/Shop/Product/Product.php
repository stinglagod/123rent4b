<?php

namespace rent\entities\Shop\Product;

use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Product\Movement\Movement;
use rent\entities\Client\Site;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\behaviors\MetaBehavior;
use rent\entities\Meta;
use rent\entities\Shop\Brand;
use rent\entities\Shop\Category;
use rent\entities\Shop\Product\Movement\Balance;
use rent\entities\Shop\Tag;
use rent\entities\AggregateRoot;
use rent\entities\EventTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\UploadedFile;
use rent\entities\Client\Client;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\Shop\Product\queries\ProductQuery;
use Yii;
use rent\entities\User\WishlistItem;
use rent\helpers\PriceHelper;

/**
 * @property integer $id
 * @property integer $created_at
 * @property string $code
 * @property string $name
 * @property string $description
 * @property integer $category_id
 * @property integer $brand_id
 * @property float $priceSale_new
 * @property float $priceSale_old
 * @property float $priceRent_new
 * @property float $priceRent_old
 * @property float $priceCost
 * @property integer $rating
 * @property integer $main_photo_id
 * @property integer $site_id
 * @property integer $status
 * @property float $priceRent
 * @property float $priceSale
 * @property string $priceRent_text
 * @property string $priceSale_text
 * @property integer $on_site
 *
 * @property \rent\entities\Client\Site $site
 * @property Meta $meta
 * @property Brand $brand
 * @property Category $category
 * @property CategoryAssignment[] $categoryAssignments
 * @property Category[] $categories
 * @property TagAssignment[] $tagAssignments
 * @property Tag[] $tags
 * @property RelatedAssignment[] $relatedAssignments
 * @property Modification[] $modifications
 * @property Value[] $values
 * @property Photo[] $photos
 * @property Photo $mainPhoto
 * @property Review[] $reviews
 * @property Movement[] $movements
 */
class Product extends ActiveRecord implements AggregateRoot
{
    use EventTrait;

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    const ON_SITE = 1;
    const OFF_SITE = 0;

    public $meta;

    public static function create($brandId = null, $categoryId, $code, $name, $description, Meta $meta): self
    {
        $product = new static();
        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->code = $code;
        $product->name = $name;
        $product->description = $description;
        $product->meta = $meta;
        $product->created_at = time();
        $product->status = self::STATUS_ACTIVE;
        return $product;
    }

    public function setPriceCost($cost): void
    {
        $this->priceCost = $cost;
    }

    public function setPriceSale($new, $old): void
    {
        $this->priceSale_new = $new;
        $this->priceSale_old = $old;
    }

    public function setPriceRent($new, $old): void
    {
        $this->priceRent_new = $new;
        $this->priceRent_old = $old;
    }

    public function edit($brandId = null, $code, $name, $onSite, $description, Meta $meta): void
    {
        $this->brand_id = $brandId;
        $this->code = $code;
        $this->name = $name;
        $this->on_site = $onSite;
        $this->description = $description;
        $this->meta = $meta;
    }

    public function changeMainCategory($categoryId): void
    {
        $this->category_id = $categoryId;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('Product is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft(): void
    {
        if ($this->isDraft()) {
            throw new \DomainException('Product is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isIdEqualTo($id):bool
    {
        return $this->id == $id;
    }

    public function isDraft(): bool
    {
        return $this->status == self::STATUS_DRAFT;
    }

    public function setValue($id, $value): void
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                $val->change($value);
                $this->values = $values;
                return;
            }
        }
        $values[] = Value::create($id, $value);
        $this->values = $values;
    }

    public function getValue($id): Value
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                return $val;
            }
        }
        return Value::blank($id);
    }

    public function isOnSite():bool
    {
        return boolval($this->on_site);
    }
    public function onSite():void
    {
        if ($this->isOnSite())
            throw new \DomainException('Product is already on Site.');

        $this->on_site=1;
        $this->onSiteCategories();

    }
    public function offSite():void
    {
        if (!$this->isOnSite())
            throw new \DomainException('Product is already not on Site.');

        $this->on_site=0;
        $this->offSiteCategories();

    }
    private function onSiteCategories():void
    {

        $assignments = $this->categoryAssignments;

        $category=$this->category;
        if (!$this->category->isOnSite()) {
            $category->onSite();
        }
        $this->category=$category;

        $categories=$this->categories;
        foreach ($categories as $category) {
            if (!$category->isOnSite()) {
                $category->onSite();
            }
        }
        $this->categories=$categories;
    }
    private function offSiteCategories():void
    {
        $category=$this->category;
        if ($this->category->isOnSite()) {
            $category->offSite($this->id);
        }
        $this->category=$category;

        $categories=$this->categories;
        foreach ($categories as $category) {
            if ($category->isOnSite()) {
                $category->offSite($this->id);
            }
        }
        $this->categories=$categories;
    }

###Modification

    public function getModification($id): Modification
    {
        foreach ($this->modifications as $modification) {
            if ($modification->isIdEqualTo($id)) {
                return $modification;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function addModification($code, $name, $price): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $modification) {
            if ($modification->isCodeEqualTo($code)) {
                throw new \DomainException('Modification already exists.');
            }
        }
        $modifications[] = Modification::create($code, $name, $price);
        $this->modifications = $modifications;
    }

    public function editModification($id, $code, $name, $price): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $i => $modification) {
            if ($modification->isIdEqualTo($id)) {
                $modification->edit($code, $name, $price);
                $this->modifications = $modifications;
                return;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function removeModification($id): void
    {
        $modifications = $this->modifications;
        foreach ($modifications as $i => $modification) {
            if ($modification->isIdEqualTo($id)) {
                unset($modifications[$i]);
                $this->modifications = $modifications;
                return;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    // Categories

    public function assignCategory($id): void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForCategory($id)) {
                return;
            }
        }
        $assignments[] = CategoryAssignment::create($id);
        $this->categoryAssignments = $assignments;
    }

    public function revokeCategory($id): void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForCategory($id)) {
                unset($assignments[$i]);
                $this->categoryAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeCategories(): void
    {
        $this->categoryAssignments = [];
    }

    // Tags

    public function assignTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForTag($id)) {
                return;
            }
        }
        $assignments[] = TagAssignment::create($id);
        $this->tagAssignments = $assignments;
    }

    public function revokeTag($id): void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForTag($id)) {
                unset($assignments[$i]);
                $this->tagAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeTags(): void
    {
        $this->tagAssignments = [];
    }

    // Photos

    public function addPhoto(UploadedFile $file): void
    {
        $photos = $this->photos;
        $photos[] = Photo::create($file);
        $this->updatePhotos($photos);
    }

    public function addPhotoByPath($name, $path): void
    {
        $photos = $this->photos;
        $newPhoto = new Photo();
        $newPhoto->file = $name;
        $photos[] = $newPhoto;
        $this->updatePhotos($photos);
    }

    public function removePhoto($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                unset($photos[$i]);
                $this->updatePhotos($photos);
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    public function removePhotos(): void
    {
        $this->updatePhotos([]);
    }

    public function movePhotoUp($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                if ($prev = $photos[$i - 1] ?? null) {
                    $photos[$i - 1] = $photo;
                    $photos[$i] = $prev;
                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    public function movePhotoDown($id): void
    {
        $photos = $this->photos;
        foreach ($photos as $i => $photo) {
            if ($photo->isIdEqualTo($id)) {
                if ($next = $photos[$i + 1] ?? null) {
                    $photos[$i] = $next;
                    $photos[$i + 1] = $photo;
                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    private function updatePhotos(array $photos): void
    {
        foreach ($photos as $i => $photo) {
            $photo->setSort($i);
        }
        $this->photos = $photos;
        $this->populateRelation('mainPhoto', reset($photos));
    }

    // Related products

    public function assignRelatedProduct($id): void
    {
        $assignments = $this->relatedAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForProduct($id)) {
                return;
            }
        }
        $assignments[] = RelatedAssignment::create($id);
        $this->relatedAssignments = $assignments;
    }

    public function revokeRelatedProduct($id): void
    {
        $assignments = $this->relatedAssignments;
        foreach ($assignments as $i => $assignment) {
            if ($assignment->isForProduct($id)) {
                unset($assignments[$i]);
                $this->relatedAssignments = $assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    // Reviews

    public function addReview($userId, $vote, $text): void
    {
        $reviews = $this->reviews;
        $reviews[] = Review::create($userId, $vote, $text);
        $this->updateReviews($reviews);
    }

    public function editReview($id, $vote, $text): void
    {
        $this->doWithReview($id, function (Review $review) use ($vote, $text) {
            $review->edit($vote, $text);
        });
    }

    public function activateReview($id): void
    {
        $this->doWithReview($id, function (Review $review) {
            $review->activate();
        });
    }

    public function draftReview($id): void
    {
        $this->doWithReview($id, function (Review $review) {
            $review->draft();
        });
    }

    private function doWithReview($id, callable $callback): void
    {
        $reviews = $this->reviews;
        foreach ($reviews as $review) {
            if ($review->isIdEqualTo($id)) {
                $callback($review);
                $this->updateReviews($reviews);
                return;
            }
        }
        throw new \DomainException('Review is not found.');
    }

    public function removeReview($id): void
    {
        $reviews = $this->reviews;
        foreach ($reviews as $i => $review) {
            if ($review->isIdEqualTo($id)) {
                unset($reviews[$i]);
                $this->updateReviews($reviews);
                return;
            }
        }
        throw new \DomainException('Review is not found.');
    }

    private function updateReviews(array $reviews): void
    {
        $amount = 0;
        $total = 0;

        foreach ($reviews as $review) {
            if ($review->isActive()) {
                $amount++;
                $total += $review->getRating();
            }
        }

        $this->reviews = $reviews;
        $this->rating = $amount ? $total / $amount : null;
    }

    public function getWishlistItems(): ActiveQuery
    {
        return $this->hasMany(WishlistItem::class, ['product_id' => 'id']);
    }

    public function getQuantity(int $dateTime=null): int
    {
        return $this->balance_stock();
    }

###Price
    public function getPriceRent(): float
    {
        return $this->priceRent_new ?: 0;
    }

    public function getPriceSale(): float
    {
        return $this->priceSale_new ?: 0;
    }

    public function getPriceRent_text(): string
    {
        if ($this->priceRent)
            return PriceHelper::format($this->priceRent) . ' руб./сут.';
        return '-';

    }

    public function getPriceSale_text(): string
    {
        if ($this->priceSale)
            return PriceHelper::format($this->priceSale) . ' руб.';
        return 'Под заказ';
    }
    public function getPriceByType($type_id):float
    {
        if ($type_id==OrderItem::TYPE_RENT) {
            return $this->priceRent;
        } else if ($type_id==OrderItem::TYPE_SALE) {
            return $this->priceSale;
        } else {
            throw new \DomainException('Не определен тип цены');
        }
    }
    public function getPriceByType_text($type_id):string
    {
        if ($type_id==OrderItem::TYPE_RENT) {
            return PriceHelper::format($this->priceRent). ' руб./сут.';
        } else if ($type_id==OrderItem::TYPE_SALE) {
            return PriceHelper::format($this->priceSale). ' руб.';
        } else {
            throw new \DomainException('Не определен тип цены');
        }
    }
### Balance
    public function addBalanceCorrect($qty,$datetime=null):void
    {
        $datetime=$datetime?:time();
        $movements=$this->movements;
        $movements[]=Movement::create($datetime,null,$qty,$this->id,Movement::TYPE_CORRECT,1);
        $this->movements=$movements;
    }

    public function addMovement(int $begin, int $end=null, int $qty, int $productId, int $type_id, int $active,int $dependId=null): void
    {
        $movements = $this->movements;
        if ($dependId) {
            $dependNotFound=true;
            foreach ($movements as $i => $movement) {
                if ($movement->isIdEqualTo($dependId)) $dependNotFound=false;
            }
            if ($dependNotFound) {
                throw new \DomainException('Depend movements do not exists.');
            }
        }
        $movements[] = Movement::create($begin,$end, $qty, $productId, $type_id, $active,$dependId);
        $this->movements = $movements;
    }
    public function removeMovement($id)
    {
        $movements = $this->movements;
        foreach ($movements as $i => $movement) {
            if ($movement->isIdEqualTo($id)) {
                unset($movements[$i]);
                $this->movements = $movements;
                return;
            }
        }
        throw new \DomainException('Movement is not found.');
    }

    public function canRent()
    {
        return  $this->priceRent > 0;
    }

    public function canSale()
    {
        return  $this->priceSale > 0;
    }

    ##########################

    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategoryAssignments(): ActiveQuery
    {
        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }

    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAssignments');
    }

    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getModifications(): ActiveQuery
    {
        return $this->hasMany(Modification::class, ['product_id' => 'id']);
    }

    public function getValues(): ActiveQuery
    {
        return $this->hasMany(Value::class, ['product_id' => 'id']);
    }

    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(Photo::class, ['product_id' => 'id'])->orderBy('sort');
    }

    public function getMainPhoto(): ActiveQuery
    {
        return $this->hasOne(Photo::class, ['id' => 'main_photo_id']);
    }

    public function getRelatedAssignments(): ActiveQuery
    {
        return $this->hasMany(RelatedAssignment::class, ['product_id' => 'id']);
    }

    public function getRelateds(): ActiveQuery
    {
        return $this->hasMany(Product::class, ['id' => 'related_id'])->via('relatedAssignments');
    }

    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['product_id' => 'id']);
    }

    public function getSite(): ActiveQuery
    {
        return $this->hasOne(Site::class, ['id' => 'site_id']);
    }

    public function getMovements(): ActiveQuery
    {
        return $this->hasMany(Movement::class, ['product_id' => 'id']);
    }


    public function balance(int $begin = null, int $end = null, $rent=false, $reserve = false, $withOut=null)
    {
        $begin=$begin?$begin:time();

        $balance_begin=Balance::find()->where(['product_id'=>$this->id])->andWhere(['<=','dateTime',$begin]);
        if ($rent==false) {
            $balance_begin->andWhere(['<>','typeMovement_id',Movement::TYPE_RENT_PUSH]);
            $balance_begin->andWhere(['<>','typeMovement_id',Movement::TYPE_RENT_PULL]);
        }
        if ($reserve==false) {
            $balance_begin->andWhere(['<>','typeMovement_id',Movement::TYPE_RESERVE]);
        }
        if ($withOut) {
            $balance_begin->andWhere(['<>','movement_id',$withOut]);
        }
        $qty_begin=$balance_begin->sum('qty');

        //ищем движения товара на старше даты $begin
        $qty_end=0;
        if (($end)or($rent)or($reserve)) {
            $balance_end=Balance::find()->where(['product_id'=>$this->id])->andWhere(['>','dateTime',$begin])->andWhere(['<','qty',0]);
            if ($end) {
                $balance_end->andWhere(['<=','dateTime',$end]);
            }
            if ($rent==false) {
                $balance_end->andWhere(['<>','typeMovement_id',Movement::TYPE_RENT_PUSH]);
                $balance_end->andWhere(['<>','typeMovement_id',Movement::TYPE_RENT_PULL]);
            }
            if ($reserve==false) {
                $balance_end->andWhere(['<>','typeMovement_id',Movement::TYPE_RESERVE]);
            }
            if ($withOut) {
                $balance_end->andWhere(['<>','movement_id',$withOut]);
            }
            $qty_end=$balance_end->sum('qty');
        }
        if ($qty_end<0) {
            $qty_begin+=$qty_end;
        }
        return (empty($qty_begin))?0:$qty_begin;
    }
    /**
     * Количество товаров свободно для продажи на дату
     * @param int|null $begin
     * @return int
     */
    public function balance_sale(int $begin=null,int $withOut=null):int
    {
        return self::balance($begin,null,true,true,$withOut);
    }
    public function balance_stock():int
    {
        return self::balance(time());
    }
    public function inStock():bool
    {
        return self::balance_stock()>0;
    }

    /**
     * Количество товаро свободно для аренды на промежуток времени
     * Если $reserve истина с учетом брони
     * Если $end не указано
     * Если $withOut указано, тогда не учитывать движения указанные в $withOut
     * @param int|null $begin
     * @param int|null $end
     * @param int|null $reserve
     * @param int|null $withOut
     * @return int
     */
    public function balance_rent(int $begin, int $end,int $withOut=null):int
    {
        return self::balance($begin,$end,true,true,$withOut);
    }

    public function canReserve(int $begin,int $end=null,int $qty):bool
    {
        if ($end){
            if ($this->balance_rent($begin,$end)<$qty)
                return false;
        } else {
            if ($this->balance_sale($begin)<$qty)
                return false;
        }
        return true;
    }
    public function canPushRent(int $begin,int $end,int $qty,int $withOut=null):bool
    {
        if ($this->balance_rent($begin,$end,$withOut)<$qty)
            return false;
//            throw new \DomainException('Not in stock for rent');
        return true;
    }
    public function canPushSale(int $begin,int $qty,int $withOut=null):bool
    {
        if ($this->balance_sale($begin,$withOut)<$qty)
            return false;
//            throw new \DomainException('Not in stock for sale');
        return true;
    }

    ##########################

    public static function tableName(): string
    {
        return '{{%shop_products}}';
    }

    public function behaviors(): array
    {
        return [
            ClientBehavior::class,
            MetaBehavior::class,
            'SaveRelationsBehavior'=>
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'categoryAssignments',
                    'tagAssignments',
                    'relatedAssignments',
                    'modifications',
                    'values',
                    'photos',
                    'reviews',
                    'movements',
                    'categories',
                    'category'
                ],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function beforeDelete(): bool
    {
        if (parent::beforeDelete()) {
            foreach ($this->photos as $photo) {
                $photo->delete();
            }
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes): void
    {
        $related = $this->getRelatedRecords();
        parent::afterSave($insert, $changedAttributes);
        if (array_key_exists('mainPhoto', $related)) {
            $this->updateAttributes(['main_photo_id' => $related['mainPhoto'] ? $related['mainPhoto']->id : null]);
        }
    }

    public static function find()
    {
        return (new ProductQuery(static::class))->alias('p')->andwhere(['p.site_id' => Yii::$app->params['siteId']]);
    }

}