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
        \Yii::$app->settings->initSite($event->site_id);
        $entity=$event->className::findOne($event->id);

        if ($event->entity instanceof Product) {

            if ($entity->isActive()) {
                $this->indexer->index($entity);
            } else {
                $this->indexer->remove($entity);
            }
            TagDependency::invalidate($this->cache, ['products']);
        }
    }
}