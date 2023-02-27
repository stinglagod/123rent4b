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
    public function findByNamePhoneEmail($name, $phone, $email,$all=false)
    {
        if ((empty($name)) and (empty($phone)) and (empty($email))) return null;
        return $this->findOneBy(['name'=>$name,'telephone'=>$phone, 'email'=>$email],$all);
    }

###
    private function getBy(array $condition): Contact
    {
        if (!$entity = Contact::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Contact not found.');
        }
        return $entity;
    }

    private function findOneBy(array $condition,$all=false): ?Contact
    {
        return Contact::find($all)->andWhere($condition)->limit(1)->one();
    }

    private function findBy(array $condition): array
    {
        return Contact::find()->andWhere($condition)->limit(1)->all();
    }

}