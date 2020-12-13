<?php

namespace rent\jobs;

class AsyncEventJob extends Job
{
    public $event;

    public function __construct($event)
    {
        // отцепляем behavior от eventа
        // сделано это из-за анонимных функция в SaveRelationsBehavior, которые не сериализуются
        if (method_exists($event->entity,'detachBehavior')) {
            $event->entity->detachBehavior('SaveRelationsBehavior');
        }
        $this->event = $event;
    }
}