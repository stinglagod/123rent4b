<?php
namespace console\controllers;

use rent\entities\Client\Client;
use rent\entities\Meta;
use Yii;
use yii\console\Controller;
use yii\helpers\Inflector;

class RefactorController extends Controller
{
    /**
     * Перенос категорий из таблицы {{%category}} в  {{%shop_categories}}}
     */
    public function actionCategories($client_id)
    {
        if ($num=self::importCategories($client_id)) {
            echo "Import categories: $num\n";
        }

    }

    public function actionProducts($client_id)
    {
        if ($num=self::importProducts($client_id)) {
            echo "Import products: $num\n";
        }
    }

//    private function

    private function importCategories($client_id) :int
    {
        if (!Client::find()->andWhere(['id'=>$client_id])->exists()) return false;

        $oldCategories=\common\models\Category::find()->where(['client_id'=>$client_id])->orderBy('lft')->all();

        $num=0;
        /** @var \common\models\Category $oldCategory */
        foreach ($oldCategories as $oldCategory) {
            if ($oldCategory->isRoot()) continue;
            $oldParent=$oldCategory->parents()->orderBy(["depth" => SORT_DESC])->one();

            if ($newCategory=\rent\entities\Shop\Category::findOne($oldCategory->id)) {
                $newCategory->edit(
                    $oldCategory->name,
                    Inflector::slug($oldCategory->name),
                    $oldCategory->name,
                    '',
                    new Meta('','','')
                );
            } else {
                $newCategory=\rent\entities\Shop\Category::create(
                    $oldCategory->name,
                    Inflector::slug($oldCategory->name),
                    $oldCategory->name,
                    '',
                    new Meta('','','')
                );
                $newCategory->id=$oldCategory->id;
            }

            $newCategory->client_id=$client_id;
            $newParent=\rent\entities\Shop\Category::findOne($oldParent->id);
            $newCategory->appendTo($newParent);

            if ($newCategory->save()) {
                $num++;
            } else {
                return false;
            }

//            exit;
        }
        return $num;
    }

    private function importProducts($client_id) :int
    {
        $oldProducts=\common\models\Product::find()->where(['clietn_id'])
        return 1;
    }
}