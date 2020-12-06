<?php

namespace rent\listeners\Shop\Product;

use rent\entities\Shop\Product\Product;
use rent\repositories\events\EntityRemoved;
use rent\services\search\ProductIndexer;
use yii\caching\Cache;
use yii\caching\TagDependency;

class ProductSearchRemoveListener
{
    private $indexer;
    private $cache;

    public function __construct(ProductIndexer $indexer, Cache $cache)
    {
        $this->indexer = $indexer;
        $this->cache = $cache;
    }

    public function handle(EntityRemoved $event): void
    {
        \Yii::$app->params['siteId']=$event->entity->site_id;
        if ($event->entity instanceof Product) {
            $this->indexer->remove($event->entity);
            TagDependency::invalidate($this->cache, ['products']);
        }
    }
}