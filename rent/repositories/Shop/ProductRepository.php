<?php

namespace rent\repositories\Shop;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\Shop\Product\Product;
use rent\repositories\NotFoundException;
use rent\dispatchers\EventDispatcher;
use rent\repositories\events\EntityPersisted;
use rent\repositories\events\EntityRemoved;

class ProductRepository
{
    private $dispatcher;
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function get($id): Product
    {
        if (!$product = Product::findOne($id)) {
            throw new NotFoundException('Product is not found.');
        }
        return $product;
    }

    public function existsByBrand($id): bool
    {
        return Product::find()->andWhere(['brand_id' => $id])->exists();
    }

    public function existsByMainCategory($id): bool
    {
        return Product::find()->andWhere(['category_id' => $id])->exists();
    }

    public function save(Product $product): void
    {
        if (!$product->save()) {
            throw new \RuntimeException('Saving error.');
        }
        $this->dispatcher->dispatchAll($product->releaseEvents());
//        $product->detachBehaviors();
//        $product->detachBehavior('SaveRelationsBehavior');
//        var_dump('tut');exit;
        $this->dispatcher->dispatch(new EntityPersisted($product));
    }

    public function remove(Product $product): void
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Removing error.');
        }
        $this->dispatcher->dispatchAll($product->releaseEvents());
//        $product->detachBehavior('SaveRelationsBehavior');
        $this->dispatcher->dispatch(new EntityRemoved($product));
    }
}