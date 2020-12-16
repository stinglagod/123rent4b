<?php

namespace rent\repositories\events;

class EntityRemoved
{
    public $entity;
    public $id;
    public $className;
    public $site_id;

    public function __construct($entity)
    {
        $this->entity = $entity;
        $this->className=get_class($entity);
        $this->id=$entity->id;
        $this->site_id=$entity->site_id;
    }
}