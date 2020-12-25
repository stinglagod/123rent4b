<?php
namespace console\controllers;

use http\Exception\RuntimeException;
use rent\entities\Client\Site;
use rent\entities\Shop\Product\Movement\Movement;
use rent\entities\Shop\Product\Product;
use Yii;
use yii\console\Controller;

class ServiceController extends Controller
{
    /**
     * Активация всех движений
     */
    public function actionActivationAllMovements($movement_id=null)
    {

        $query=Movement::find()->andWhere(['active'=>0])->orderBy('created_at');
        if ($movement_id) {
            $query->andWhere(['id'=>$movement_id]);
        }
        if ($movements=$query->all()) {
            /** @var Movement $movement */
            foreach ($movements as $movement) {
                $this->updateSettings($movement->site_id);
                $movement->active=1;
                echo $movement->id.' | '. $movement->product_id ."\n";
//                var_dump(Product::findOne(624));
                $movement->save();
            }
        }
    }

    /**
     * Перенос цену продажи в цену компенсационную.
     * Попросили Свадебаня фея
     * @param $site_id
     */
    public function actionSellPriceToCompensationPrice($site_id)
    {
        $this->updateSettings($site_id);
        echo \Yii::$app->settings->site->name . PHP_EOL;
        $products=Product::find()->all();
        $num=0;
        /** @var Product $product */
        foreach ($products as $product) {
            if ($product->priceSale_new) {
                echo "Change " . $product->id . PHP_EOL;
                $product->priceCompensation=$product->priceSale_new;
                $product->priceSale_new=null;
                if ($product->save()) {
                    $num++;
                } else {
                    throw new \RuntimeException('Don not save product');
                }

            }
        }
        echo "Total change products: " . $num . PHP_EOL;
    }

    private function updateSettings($site_id):void
    {
        if (!$site=Site::findOne($site_id)) throw new \DomainException('Don not find site');

        \Yii::$app->settings->initSite((int)$site_id);
    }


}