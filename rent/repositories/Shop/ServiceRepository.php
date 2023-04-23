<?php

namespace rent\repositories\Shop;

use rent\entities\Shop\Service;
use rent\repositories\NotFoundException;

class ServiceRepository
{
    public function get($id): Service
    {
        if (!$entity = Service::findOne($id)) {
            throw new NotFoundException('Услуга не найдена.');
        }
        return $entity;
    }

    public function save(Service $entity): void
    {
        if (!$entity->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(Service $entity): void
    {
        if (!$entity->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }
    }
}