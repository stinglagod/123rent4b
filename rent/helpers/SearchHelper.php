<?php

namespace rent\helpers;

use rent\entities\Client\Site;

class SearchHelper
{
    private const INDEXNAME = 'shop_';

    public static function indexName($site_id=null): string
    {
        if ($site_id) {
            if (!$site=Site::findOne($site_id)) {
                throw new \RuntimeException('Dont find site.');
            }
            $site_id=$site->id;
        } else {
            $site_id=\Yii::$app->params['siteId'];
        }
        return self::INDEXNAME.$site_id;
    }
} 