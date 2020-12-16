<?php

namespace rent\listeners;

use rent\repositories\events\EntityPersisted;
use rent\entities\Shop\Product\Product;

class TestListener
{

    public function handle(EntityPersisted $event): void
    {
//        \Yii::warning('TestListener');
//        if ($event->entity instanceof Product) {
//            \Yii::warning($event->entity);
//            \Yii::warning($event->entity->getCategory());
//            \Yii::warning($event->entity->category->id);
//        }
//        file_put_contents(__DIR__.'/log.log','TestListener');
    }
}