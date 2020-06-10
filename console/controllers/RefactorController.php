<?php
namespace console\controllers;

use common\models\Category;
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

    /**
     * Полная очистка категория в таблице {{%shop_categories}}
     * Если указан идентификатор тогда удаляется категория вместе со всеми вложенными
     * @param null $category_id
     * @throws \yii\db\Exception
     */
    public function actionDeleteCategory($client_id,$category_id=null)
    {
        if (!$client=Client::findOne($client_id)) return false;

        Yii::$app->params['siteId']=$client->getFirstSite()->id;

        $categories=\rent\entities\Shop\Category::find();
        if ($category_id)
            $categories->andWhere(['id'=>$category_id]);
        $categories=$categories->orderBy(["depth" => SORT_DESC])->all();
        /** @var \rent\entities\Shop\Category $category */
        foreach ($categories as $category) {
            if ($category->isRoot()) continue;
            $category->deleteWithChildren();
        }
    }

//    private function

    private function importCategories($client_id) :int
    {
        if (!$client=Client::findOne($client_id)) return false;
        $site_id=$client->getFirstSite()->id;
        Yii::$app->params['siteId']=$client->getFirstSite()->id;
        $oldCategories=\common\models\Category::find()->orderBy('lft')->all();

        $num=0;
        /** @var \common\models\Category $oldCategory */
        foreach ($oldCategories as $oldCategory) {
            if ($oldCategory->isRoot()) continue;
            $oldParent=$oldCategory->parents()->orderBy(["depth" => SORT_DESC])->one();

            if ($newCategory=\rent\entities\Shop\Category::findOne((1 + $oldCategory->id))) {
                $newCategory->edit(
                    $oldCategory->name,
                    Inflector::slug($oldCategory->name),
                    $oldCategory->name,
                    '',
                    new Meta('','','')
                );
            } else {
                $slug=$this->getSlug($oldCategory->name);Inflector::slug($oldCategory->name);
                if (\rent\entities\Shop\Category::findBySlug($slug))
                    $slug.='2';

                $newCategory=\rent\entities\Shop\Category::create(
                    $oldCategory->name,
                    $slug,
                    $oldCategory->name,
                    '',
                    new Meta('','','')
                );
                $newCategory->id=(1+$oldCategory->id);
            }

            $newCategory->site_id=$site_id;
            $newParent=\rent\entities\Shop\Category::findOne(1+$oldParent->id);
            $newCategory->appendTo($newParent);

            if ($newCategory->save()) {
                $num++;
            } else {
                return $newCategory->errors[0];
            }


//            exit;
        }
        return $num;
    }

    private function getSlug ($slug):string
    {
        if (\rent\entities\Shop\Category::findBySlug($slug)) {
            $slug=self::getSlug($slug.'1');
        }
        return $slug;
    }
    private function importProducts($client_id) :int
    {
        $oldProducts=\common\models\Product::find()->where(['clietn_id']);
        return 1;
    }
}