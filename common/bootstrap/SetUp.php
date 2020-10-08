<?php
/**
 * Created by PhpStorm.
 * User: Aleksey
 * Date: 27.05.2020
 * Time: 14:15
 */

namespace common\bootstrap;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
//use rent\services\ContactService;
use rent\cart\Cart;
use rent\cart\cost\calculator\DynamicCost;
use rent\cart\cost\calculator\SimpleCost;
use rent\cart\storage\HybridStorage;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
use yii\caching\Cache;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });
        $container->setSingleton(Cache::class, function () use ($app) {
            return $app->cache;
        });
        $container->setSingleton(Client::class, function () {
            return ClientBuilder::create()->build();
        });
        $container->setSingleton(Cart::class, function () use ($app) {
            return new Cart(
                new HybridStorage($app->get('user'), 'cart', 3600 * 24, $app->db),
                new DynamicCost(new SimpleCost())
            );
        });
        //setSingleton - класс создается один раз
//        $container->setSingleton(ContactService::class, [], [
//            $app->params['adminEmail']
//        ]);
    }

}