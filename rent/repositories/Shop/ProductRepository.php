<?php

namespace rent\repositories\Shop;

use rent\entities\Shop\Product\Product;
use rent\repositories\NotFoundException;
use rent\services\search\ProductIndexer;

class ProductRepository
{
    private $indexer;
    public function __construct(ProductIndexer $indexer)
    {
        $this->indexer=$indexer;
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
        $this->indexer->reindex($product);

    }

    public function remove(Product $product): void
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}