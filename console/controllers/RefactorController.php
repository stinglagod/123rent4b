<?php
namespace console\controllers;

use common\models\Category;
use common\models\File;
use rent\entities\Client\Client;
use rent\entities\Meta;
use rent\entities\Shop\Characteristic;
use rent\entities\Shop\Product\Movement\Action;
use rent\entities\Shop\Product\Photo;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Tag;
use rent\forms\manage\Shop\Product\PhotosForm;
use rent\repositories\Shop\CharacteristicRepository;
use rent\services\manage\Shop\ProductManageService;
use Yii;
use yii\console\Controller;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class RefactorController extends Controller
{
    /**
     * Полный перенос
     */
    public function actionAll($client_id)
    {
        if ($num=self::importCategories($client_id)) {
            echo "Import categories: $num\n";
        }
        if ($num=self::importCharacteristics($client_id)) {
            echo "Import Characteristics: $num\n";
        }
        if ($num=self::importTags($client_id)) {
            echo "Import Tags: $num\n";
        }
        if ($num=self::importProducts($client_id)) {
            echo "Import products: $num\n";
        }

    }
    /**
     * Перенос категорий из таблицы {{%category}} в  {{%shop_categories}}}
     */
    public function actionCategories($client_id)
    {
        if ($num=self::importCategories($client_id)) {
            echo "Import categories: $num\n";
        }

    }
    /**
     * Перенос категорий из таблицы {{%attribute}} в  {{%shop_characteristics}}}
     */
    public function actionCharacteristics($client_id)
    {
        if ($num=self::importCharacteristics($client_id)) {
            echo "Import Characteristics: $num\n";
        }
    }
    /**
     * Перенос категорий из таблицы {{%tag}} в  {{%shop_tags}}}
     */
    public function actionTags($client_id)
    {
        if ($num=self::importTags($client_id)) {
            echo "Import Tags: $num\n";
        }
    }
    /**
     * Перенос категорий из таблицы {{%product}} в  {{%shop_products}}}
     */
    public function actionProducts($client_id)
    {
        if ($num=self::importProducts($client_id)) {
            echo "Import products: $num\n";
        }
    }

    /**
     * Перенос категорий из таблицы {{%action}} в  {{%shop_actions}}}
     */
    public function actionActions($client_id)
    {
        if ($num=self::importActions($client_id)) {
            echo "Import actions: $num\n";
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
        $num=0;
        /** @var \rent\entities\Shop\Category $category */
        foreach ($categories as $category) {
            if ($category->isRoot()) continue;
            $category->deleteWithChildren();
            $num++;
        }
        echo "Удалено категорий: $num\n";
    }

    /**
     * Удаляем ВСЕ товары в {{%shop_products}}}
     */
    public function actionCleanProducts($client_id)
    {
        if (!$client=Client::findOne($client_id)) return false;
        Yii::$app->params['siteId']=$client->getFirstSite()->id;

        if ($newProducts=\rent\entities\Shop\Product\Product::find()->all()) {
            $num=0;
            foreach ($newProducts as $product) {
                $product->delete();
                $num++;
            }
            echo "Удалено товаров: $num\n";
        } else {
            echo "Нет товаров для удаления";
        }

    }


################################################################
//    private function

    private function importCategories($client_id) :int
    {
        if (!$client=Client::findOne($client_id)) return false;
        if (!$site_id=$client->getFirstSite()->id) return false;
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
                $slug=$this->getSlug(Inflector::slug($oldCategory->name));
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
        if (!$client=Client::findOne($client_id)) return false;
        if (!$site_id=$client->getFirstSite()->id) return false;
        Yii::$app->params['siteId']=$site_id;

        $oldProducts=\common\models\Product::find()->all();
        $num=0;

        /** @var \common\models\Product $oldProduct */
        foreach ($oldProducts as $oldProduct) {
            if (empty($oldProduct->name)) continue;


            if ($newProduct=\rent\entities\Shop\Product\Product::findOne($oldProduct->id)) {
                $newProduct->delete();
            }

            if (!$firstCategory=$oldProduct->getCategories()->one()) continue;

            /** @var Product $newProduct */
            $newProduct=Product::create(
                null,
                (1+$firstCategory->id),
                self::getCode($oldProduct->cod,$oldProduct->name),
                $oldProduct->name,
                $oldProduct->description,
                new Meta('','','')
            );
//          categories
            foreach ($oldProduct->categories as $category) {
                if ($category->id === $firstCategory->id) continue;
                $newProduct->assignCategory(1+$category->id);
            }
//          tags
            foreach ( $oldProduct->getTagsArray() as $tag) {
                $tag=Tag::find()->where(['name'=>$tag])->one();
                $newProduct->assignTag($tag->id);
            }
//          characteristics
            foreach ($oldProduct->getProductAttributes()->all() as $attr) {
                $newProduct->setValue($attr->attribute_id,$attr->value);
            }

            $newProduct->id=$oldProduct->id;
            $newProduct->site_id=$site_id;
            $newProduct->priceSale_new=$oldProduct->priceSale;
            $newProduct->priceRent_new=$oldProduct->priceRent;
            $newProduct->priceCost=$oldProduct->pricePrime;
            $newProduct->status=Product::STATUS_ACTIVE;
            if ($newProduct->save()){
                $files=$oldProduct->getFiles();
//                $newProduct->tag
                if ($mainImage_id=self::addPhotos($files,$newProduct->id)){
                    $newProduct->main_photo_id=$mainImage_id;
                    $newProduct->save();
                }

            }
        }
        return $num;
    }

    private function importCharacteristics($client_id) :int
    {
        if (!$client=Client::findOne($client_id)) return false;
        if (!$site_id=$client->getFirstSite()->id) return false;
        $oldAttributes=\common\models\Attribute::find()->all();
        $num=0;

        /** @var \common\models\Attribute $oldAttribute */
        foreach ($oldAttributes as $oldAttribute) {
            if ($newCharacteristic=\rent\entities\Shop\Characteristic::findOne($oldAttribute->id)) {
                $newCharacteristic->edit(
                    $oldAttribute->name,
                    \rent\entities\Shop\Characteristic::TYPE_STRING,
                    false,
                    null,
                    array(),
                    1
                );
                $newCharacteristic->site_id=$site_id;
            } else {
                $newCharacteristic=\rent\entities\Shop\Characteristic::create(
                    $oldAttribute->name,
                    \rent\entities\Shop\Characteristic::TYPE_STRING,
                    false,
                    null,
                    array(),
                    1
                );
                $newCharacteristic->id=$oldAttribute->id;
                $newCharacteristic->site_id=$site_id;
            }
            if ($newCharacteristic->save()) {
                $num++;
            } else {
                return $newCharacteristic->errors[0];
            }
        }
        return $num;
    }

    private function importTags($client_id) :int
    {
        if (!$client=Client::findOne($client_id)) return false;
        if (!$site_id=$client->getFirstSite()->id) return false;
        $oldTags=\common\models\Tag::find()->all();
        $num=0;
        /** @var \common\models\Tag $oldTag */
        foreach ($oldTags as $oldTag) {
            if ($newTag=\rent\entities\Shop\Tag::findOne($oldTag->id)) {
                $newTag->edit(
                    $oldTag->name,
                    $this->getSlug(Inflector::slug($oldTag->name))
                );
            } else {
                $newTag=Tag::create(
                    $oldTag->name,
                    $this->getSlug(Inflector::slug($oldTag->name))
                );
                $newTag->id=$oldTag->id;
            }
            $newTag->site_id=$site_id;
            if ($newTag->save()){
                $num++;
            }
        }
        return $num;
    }


    private function addPhotos($files,$product_id)
    {
        $num=1;
        $first_id=null;
        /** @var \common\models\File $file */
        foreach ($files as $file) {
            if (!is_file($file->getPath())) continue;
            if ($num==1) {
                $first_id=$file->id;
            }
            /** @var Photo $newPhoto */
            $newPhoto=new Photo();
            $newPhoto->id=$file->id;
            $newPhoto->file=$file->name;
            $newPhoto->product_id=$product_id;
            $newPhoto->sort=$num;
            $num++;
            if ($newPhoto->save()) {
                $newPath=Yii::getAlias('@staticRoot/origin/products/'.self::makeIdPath($newPhoto->id).'/');
                echo $newPath;echo "\n";
                if (!is_dir($newPath))
                    mkdir($newPath,'0750',true);
                copy($file->getPath(),$newPath.$file->id.'.'.$file->ext);
                $newPhoto->createThumbs();
            }
        }

        return $first_id;
    }
    private function getCode($code,$name)
    {
        if (empty($code)) {
            $code=Inflector::slug($name);
        }

        if (Product::find()->where(['code'=>$code])->exists()) {
            $code=self::getCode($code.'_1',$name);
        }

        return $code;
    }

    /**
     * @param integer $id
     * @return string
     */
    protected static function makeIdPath($id)
    {
        $id = is_array($id) ? implode('', $id) : $id;
        $length = 10;
        $id = str_pad($id, $length, '0', STR_PAD_RIGHT);

        $result = [];
        for ($i = 0; $i < $length; $i++) {
            $result[] = substr($id, $i, 1);
        }

        return implode('/', $result);
    }

    private function importActions($client_id) :int
    {
        if (!$client=Client::findOne($client_id)) return false;
        if (!$site_id=$client->getFirstSite()->id) return false;


       if ($newActions=Action::find()->all()) {
           foreach ($newActions as $newAction) {
               $newAction->delete();
           }
       }

        $oldActions=\common\models\Action::find()->all();
        $num=0;
        /** @var \common\models\Action $oldAction */
        foreach ($oldActions as $oldAction) {
            $newAction= new Action();
            $newAction->id=$oldAction->id;
            $newAction->name=$oldAction->name;
            $newAction->sing=$oldAction->sing;
            $newAction->shortName=$oldAction->shortName;
            $newAction->sequence=$oldAction->sequence;
            $newAction->order=$oldAction->order;
            $newAction->antipod_id=$oldAction->antipod_id;
            $newAction->actionType_id=$oldAction->actionType_id;
            $newAction->save();
            $num++;
        }
        return $num;
    }
}