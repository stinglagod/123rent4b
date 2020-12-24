<?php
namespace console\controllers;

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

    private function updateSettings($site_id):void
    {
        if (!$site=Site::findOne($site_id)) throw new \DomainException('Don not find site');

        \Yii::$app->settings->siteInit($site_id);
    }
}