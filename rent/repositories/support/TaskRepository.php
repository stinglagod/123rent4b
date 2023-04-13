<?php

namespace rent\repositories\support;

use rent\entities\Shop\Brand;
use rent\entities\Support\Task\Task;
use rent\repositories\NotFoundException;

class TaskRepository
{
    public function get($entityOrId): Task
    {
        if (is_a($entityOrId,Task::class)) {
            return $entityOrId;
        } else {
            return $this->getBy(['id' => $entityOrId]);
        }
    }

    public function save(Task $entity): void
    {
        if (!$entity->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Task $entity): void
    {
        if (!$entity->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function find($entityOrId):?Task
    {
        if (is_a($entityOrId,Task::class)) {
            return $entityOrId;
        } else {
            return Task::findOne($entityOrId);
        }
    }
###
    private function getBy(array $condition): Task
    {
        if (!$entity = Task::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Task not found.');
        }
        return $entity;
    }
}