<?php

namespace rent\cart\storage;

use rent\cart\CartItem;
use rent\entities\Shop\Product\Product;
use Yii;
use yii\helpers\Json;
use yii\web\Cookie;

class CookieStorage implements StorageInterface
{
    private $key;
    private $timeout;

    public function __construct($key, $timeout)
    {
        $this->key = $key;
        $this->timeout = $timeout;
    }

    public function load(): array
    {
        if ($cookie = Yii::$app->request->cookies->get($this->key)) {
            return array_filter(array_map(function (array $row) {
                if (isset($row['p'], $row['q'],$row['t'],$row['pr']) && $product = Product::find()->active()->andWhere(['id' => $row['p']])->one()) {
                    /** @var Product $product */
                    return new CartItem($row['t'],$row['q'],null,$row['pr'],$product);
                }
                return false;
            }, Json::decode($cookie->value)));
        }
        return [];
    }

    public function save(array $items): void
    {
        Yii::$app->response->cookies->add(new Cookie([
            'name' => $this->key,
            'value' => Json::encode(array_map(function (CartItem $item) {
                return [
                    'p' => $item->getProductId(),
                    'q' => $item->getQuantity(),
                    't' => $item->getType(),
                    'pr' => $item->getPrice(),
                ];
            }, $items)),
            'expire' => time() + $this->timeout,
        ]));
    }
} 