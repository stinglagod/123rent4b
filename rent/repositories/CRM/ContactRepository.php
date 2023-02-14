<?php

namespace rent\repositories\CRM;

use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\entities\CRM\Contact;
use rent\repositories\NotFoundException;

class ContactRepository
{

    public function get($id): Contact
    {
        return $this->getBy(['id' => $id]);
    }
    /**
     * Сохраняем, если не сохраняется отправляем исключение
     * @param Contact $entity
     */
    public function save(Contact $entity): void
    {
        if (!$entity->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    private function getBy(array $condition): Contact
    {
        if (!$entity = Contact::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Contact not found.');
        }
        return $entity;
    }

}