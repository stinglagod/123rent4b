<?php

namespace rent\repositories\Client;

use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\repositories\NotFoundException;

class ClientRepository
{

    public function get($id): Client
    {
        return $this->getBy(['id' => $id]);
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
            throw new NotFoundException('Client not found.');
        }
        return $client;
    }


    public function getSite($id): Site
    {
        return $this->getSiteBy(['id' => $id]);
    }

    private function getSiteBy(array $condition): Site
    {
        if (!$site = Site::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Site not found.');
        }
        return $site;
    }
}