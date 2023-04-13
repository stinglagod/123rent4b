<?php

namespace rent\repositories\Client;

use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\repositories\NotFoundException;

class ClientRepository
{

    public function get($entityOrId): Client
    {
        if (is_a($entityOrId,Client::class)) {
            return $entityOrId;
        } else {
            return $this->getBy(['id' => $entityOrId]);
        }

    }
    /**
     * Сохраняем, если не сохраняется отправляем исключение
     * @param Client $client
     */
    public function save(Client $client): void
    {
        if (!$client->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    private function getBy(array $condition): Client
    {
        if (!$client = Client::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Клиент не найден');
        }
        return $client;
    }


    public function getSite($id): Site
    {
        return $this->getSiteBy(['id' => $id]);
    }

    public function find($entityOrId):?Client
    {
        if (is_a($entityOrId,Client::class)) {
            return $entityOrId;
        } else {
            return Client::findOne($entityOrId);
        }
    }
###
    private function getSiteBy(array $condition): Site
    {
        if (!$site = Site::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Site not found.');
        }
        return $site;
    }
}