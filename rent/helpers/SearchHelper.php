<?php

namespace rent\helpers;

use rent\entities\Client\Site;

class SearchHelper
{
    private const INDEX_NAME = 'shop_';
    private const INDEX_FRONTEND = '_frontend';
    private const INDEX_BACKEND = '_backend';

    public static function indexName(): string
    {
        if (AppHelper::isSite()) {
            return self::indexNameFrontend();
        } else {
            return self::indexNameBackend();
        }
    }

    public static function indexNameFrontend($site_id=null): string
    {
        if (empty($site_id)){
            if (\Yii::$app->settings->site) {
                $site_id=\Yii::$app->settings->site->id;
            } else {
                throw new \DomainException('Для формирования индекса сайта, нужно $site_id');
            }
        }
        return self::INDEX_NAME . $site_id . self::INDEX_FRONTEND;
    }

    public static function indexNameBackend($site_id=null): string
    {
        return self::INDEX_NAME . \Yii::$app->settings->getClientId() . self::INDEX_BACKEND;
    }

} 