<?php

namespace rent\listeners\Shop\Product;

use rent\entities\Shop\Product\Product;
use rent\repositories\events\EntityPersisted;
use rent\services\search\ProductIndexer;
use yii\caching\Cache;
use yii\caching\TagDependency;

class ProductSearchPersistListener
{
    private $indexer;
    private $cache;

    public function __construct(ProductIndexer $indexer, Cache $cache)
    {
        $this->indexer = $indexer;
        $this->cache = $cache;
    }

    public function handle(EntityPersisted $event): void
    {
        if ($event->entity instanceof Product) {
            \Yii::$app->params['siteId']=$event->entity->site_id;
            if ($event->entity->isActive()) {
                $this->indexer->index($event->entity);
            } else {
                $this->indexer->remove($event->entity);
            }
            TagDependency::invalidate($this->cache, ['products']);
        }
    }
}