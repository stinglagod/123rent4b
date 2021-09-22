<?php

namespace rent\repositories\Client;

use rent\entities\Client\Client;
use rent\entities\Client\Site;
use rent\repositories\NotFoundException;

class SiteRepository
{

    public function get($id): Site
    {
        return $this->getBy(['id' => $id]);
    }
    /**
     * Сохраняем, если не сохраняется отправляем исключение
     * @param Site $site
     */
    public function save(Site $site): void
    {
        if (!$site->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function findByDomain($domain)
    {
        return Site::find(true)->andWhere(['domain'=>$domain])->limit(1)->one();
    }

    public function findByDomainOrId($domainOrId)
    {
        if (is_int($domainOrId)) {
            return Site::find(true)->andWhere(['id'=>$domainOrId])->limit(1)->one();
        } else {
            return Site::find(true)->andWhere(['domain'=>$domainOrId])->limit(1)->one();
        }

    }
### Private
    private function getBy(array $condition): Site
    {
        if (!$site = Site::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Site not found.');
        }
        return $site;
    }


}