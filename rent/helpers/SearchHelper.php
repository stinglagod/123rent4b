<?php

namespace rent\helpers;

use rent\entities\Client\Site;

class SearchHelper
{
    private const INDEX_NAME = 'shop_';
    private const INDEX_FRONTEND = '_frontend';
    private const INDEX_BACKEND = '_backend';

    private static function name($site_id = null): string
    {
        if ($site_id) {
            if (!$site=Site::findOne($site_id)) {
                throw new \RuntimeException('Dont find site.');
            }
            $site_id=$site->id;
        } else {
            $site_id=\Yii::$app->params['siteId'];
        }
        return self::INDEX_NAME.$site_id;
    }

    public static function indexName($site_id=null): string
    {
        if (AppHelper::isSite()) {
            return self::indexNameFrontend($site_id);
        }
        return self::name($site_id);
    }

    public static function indexNameFrontend($site_id=null): string
    {
        return self::name($site_id).self::INDEX_FRONTEND;
    }

    public static function indexNameBackend($site_id=null): string
    {
        return self::name($site_id);
    }

} 