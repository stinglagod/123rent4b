<?php

namespace rent\listeners\Shop\Category;

use rent\entities\Shop\Category;
use rent\repositories\events\EntityPersisted;
use yii\caching\Cache;
use yii\caching\TagDependency;

class CategoryPersistenceListener
{
    private $cache;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    public function handle(EntityPersisted $event): void
    {
        if ($event->entity instanceof Category) {
            \Yii::$app->params['siteId']=$event->entity->site_id;
            TagDependency::invalidate($this->cache, ['categories']);
        }
    }
}