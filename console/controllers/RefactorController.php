<?php
namespace console\controllers;

use common\models\Cash;
use common\models\OrderBlock;
use common\models\OrderProduct;
use rent\cart\CartItem;
use rent\entities\Shop\Order\CustomerData;
use rent\entities\Shop\Order\DeliveryData;
use rent\entities\Shop\Order\Item\ItemBlock;
use rent\entities\Shop\Order\Item\OrderItem;
use rent\entities\Shop\Order\Item\PeriodData;
use rent\entities\Shop\Order\Order;
use rent\entities\Client\Client;
use rent\entities\Meta;
use rent\entities\Shop\Characteristic;
use rent\entities\Shop\Order\Payment;
use rent\entities\Shop\Order\Status;
use rent\entities\Shop\Service;
use rent\entities\Shop\Product\Movement\Action;
use rent\entities\Shop\Product\Movement\Movement;
use rent\entities\Shop\Product\Photo;
use rent\entities\Shop\Product\Product;
use rent\entities\Shop\Tag;
use rent\forms\manage\Shop\Order\OrderCreateForm;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\forms\manage\Shop\Product\PhotosForm;
use rent\readModels\Shop\OrderReadRepository;
use rent\repositories\Shop\CharacteristicRepository;
use rent\services\manage\Shop\OrderManageService;
use rent\services\manage\Shop\ProductManageService;
use Yii;
use yii\console\Controller;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class RefactorController extends Controller
{

    private $service;
    private $orders;

    public function __construct(
        $id,
        $module,
        OrderManageService $service,
        OrderReadRepository $orders,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->orders = $orders;
    }
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
        if ($num=self::importMovements($client_id)) {
            echo "Import movements: $num\n";
        }
        if ($num=self::importService($client_id)) {
            echo "Import services: $num\n";
        }
        if ($num=self::importBlock($client_id)) {
            echo "Import blocks: $num\n";
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
     * Перенос категорий из таблицы {{%movement}} в  {{%shop_movements}}}
     * Переносится только приход
     */
    public function actionMovement($client_id)
    {
        if ($num=self::importMovements($client_id)) {
            echo "Import movements: $num\n";
        }
    }
    /**
     * Перенос услуг из таблицы {{%service}} в  {{%shop_order_services}}}
     *
     */
    public function actionService($client_id)
    {
        if ($num=self::importService($client_id)) {
            echo "Import services: $num\n";
        }
    }

    /**
     * Перенос заказов
     *
     */
    public function actionOrder($client_id)
    {
        if ($num=self::importOrder($client_id)) {
            echo "Import orders: $num\n";
        }
    }
    /**
     * Перенос Название Блоков заказаов {{%block}} в  {{%shop_item_blocks}}}
     *
     */
    public function actionBlock($client_id)
    {
        if ($num=self::importBlock($client_id)) {
            echo "Import blocks: $num\n";
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

    private function importMovements($client_id) :int
    {
        if (!$client=Client::findOne($client_id)) return false;
        if (!$site_id=$client->getFirstSite()->id) return false;
        Yii::$app->params['siteId']=$site_id;


       if ($newMovements=Movement::find()->andWhere(['site_id'=>$site_id])->all()) {
           foreach ($newMovements as $newMovement) {
               $newMovement->delete();
           }
       }

        $oldMovements=\common\models\Movement::find()->andWhere(['action_id'=>9])->all();
        $num=0;
        /** @var \common\models\Movement $oldMovement */
        foreach ($oldMovements as $oldMovement) {
            if ($oldMovement->qty<=0) continue;
            $newMovement=Movement::create(
                strtotime($oldMovement->dateTime),
                null,
                $oldMovement->qty,
                $oldMovement->product_id,
                Movement::TYPE_INCOMING,
                true
            );

            if ($newMovement->save()) {
                $num++;
            }

        }
        return $num;
    }
    private function importService($client_id):int
    {
        if (!$client=Client::findOne($client_id)) return false;
        if (!$site_id=$client->getFirstSite()->id) return false;
        Yii::$app->params['siteId']=$site_id;

        //очищаем
        if ($newServices=Service::find()->andWhere(['site_id'=>$site_id])->all()) {
            foreach ($newServices as $newService) {
                $newService->delete();
            }
        }

        $oldServices=\common\models\Service::find()->all();
        $num=0;
        /** @var \common\models\Service $oldService */
        foreach ($oldServices as $oldService) {
            $newService=Service::create($oldService->name,$oldService->percent,$oldService->is_depend,$oldService->defaultCost);
            if ($newService->save()) {
                $num++;
            }
        }
        return $num;


    }

    private function importOrder($client_id):int
    {
        if (!$client = Client::findOne($client_id)) return false;
        if (!$site_id = $client->getFirstSite()->id) return false;
        Yii::$app->params['siteId'] = $site_id;
        //очищаем
        if ($newOrders = Order::find()->andWhere(['site_id' => $site_id])->all()) {
            foreach ($newOrders as $newOrder) {
                $newOrder->delete();
            }
        }
//        return 1;
        $oldOrders=\common\models\Order::find()->all();
        $num=0;
        $block_id=0;
        /** @var \common\models\Order $oldOrder */
        foreach ($oldOrders as $oldOrder) {
            $newOrder=Order::create(
                $oldOrder->responsible_id,
                $oldOrder->name,
                $oldOrder->cod,
                strtotime($oldOrder->dateBegin),
                strtotime($oldOrder->dateEnd),
                new CustomerData(
                    $oldOrder->telephone,
                    $oldOrder->customer,
                    ''
                ),
                new DeliveryData(
                    $oldOrder->address
                ),
                [],
                $oldOrder->getSumm(),
                $oldOrder->description
            );
            $newOrder->items=[];

            $newOrder->id=$oldOrder->id;

            #status
            $status_id=null;
            switch ($oldOrder->status_id){
                case \common\models\Status::NEW:
                    $status_id=Status::NEW;
                    break;
                case \common\models\Status::SMETA:
                    $status_id=Status::ESTIMATE;
                    break;
                case \common\models\Status::PARTISSUE:
                    $status_id=Status::PART_ISSUE;
                    break;
                case \common\models\Status::ISSUE:
                    $status_id=Status::ISSUE;
                    break;
                case \common\models\Status::RETURN:
                    $status_id=Status::RETURN;
                    break;
                case \common\models\Status::CLOSE:
                    $status_id=Status::COMPLETED;
                    break;
                case \common\models\Status::PARTRETURN:
                    $status_id=Status::PART_RETURN;
                    break;
                case \common\models\Status::CANCELORDER:
                    $status_id=Status::CANCELLED;
                    break;
                case \common\models\Status::NEWFRONTEND:
                    $status_id=Status::NEW_BY_CUSTOMER;
                    break;
            }
            $newOrder->current_status=$status_id;
            $newOrder->save();
//echo 'Заказ: '.$newOrder->id."\n";
            #payments
            /** @var Cash $cash */
            foreach ($oldOrder->cashes as $cash) {
                $formPayment=new PaymentForm();
                switch ($cash->cashType_id) {
                    case 1:
                        $formPayment->type_id=Payment::TYPE_BY_CARD;
                        break;
                    case 2:
                        $formPayment->type_id=Payment::TYPE_CASH;
                        break;
                    case 3:
                        $formPayment->type_id=Payment::TYPE_TO_BANK_ACCOUNT;
                        break;
                    case 4;
                        $formPayment->purpose_id=Payment::POP_DEPOSIT;
                        break;
                }
                $formPayment->dateTime=strtotime($cash->dateTime);
                $formPayment->sum=$cash->sum;
                $formPayment->note=$cash->note;

                $this->service->addPayment($newOrder->id,$formPayment);
            }
            #items

            /** @var OrderBlock $block */
            foreach ($oldOrder->getOrderBlocks()->all() as $block) {
//                $newBlock=$this->service->addBlock($newOrder->id,$block->name);
                $newBlock=OrderItem::createBlock($block->name);
                $newBlock->id=(10000+$block_id);
                $block_id++;
                $blocks=$newOrder->blocks;
                $blocks[]=$newBlock;
                $newOrder->blocks=$blocks;
                $newOrder->save();

                $oldItems=OrderProduct::find()->where(['orderBlock_id'=>$block->id])->orderBy('id')->all();
                /** @var OrderProduct $oldItem */
                foreach ($oldItems as $oldItem) {
//echo 'Товар: '.$oldItem->id. ' Имя: '.$oldItem->name."\n";
                    if ($oldItem->parent_id==$oldItem->id) {
                        $parent=$newBlock;
                    } else {
                        $parent=OrderItem::findOne($oldItem->parent_id);
                    }
                    $newItem=new OrderItem();
                    $newItem->id=$oldItem->id;
                    $newItem->order_id=$oldItem->order_id;
                    $newItem->product_id=$oldItem->product_id;
                    $newItem->name=$oldItem->name;
                    $newItem->qty=$oldItem->qty;
                    $newItem->price=$oldItem->cost;
                    $newItem->periodData=($oldItem->period)?new PeriodData($oldItem->period):null;
                    $newItem->parent_id=$parent->id;
                    $newItem->note=$oldItem->comment;
                    $newItem->is_montage=$oldItem->is_montage;
                    $newItem->service_id=$oldItem->service_id;
                    $newItem->current_status=$status_id;

                    switch ($oldItem->type) {
                        case 'rent':
                            $newItem->type_id=OrderItem::TYPE_RENT;
                            break;
                        case 'sale':
                            $newItem->type_id=OrderItem::TYPE_SALE;
                            // Если была продажа товара из каталога, тогда надо создать движения
                            if ($oldItem->product_id) {
                                $newProduct=Product::findOne($oldItem->product_id);

                                if ($newProduct->balance_sale() != $oldItem->product->getBalanceStock()) {
                                    echo "Продажа у заказа #".$oldOrder->id.' Товар #'.$oldItem->product_id;
                                    echo " Остатки НЕ сходятся\n";
                                }
//                                else {
//                                    echo "Продажа у заказа #".$oldOrder->id;
//                                    echo " Остатки сходятся\n";
//                                }
                            }
                            break;
                        case 'service':
                            $newItem->type_id=OrderItem::TYPE_SERVICE;
                            break;
                        case 'collect':
                            $newItem->type_id=OrderItem::TYPE_COLLECT;
                            break;
                    }
                    $newItem->save();
                }
            }
            #service
            $oldItems=OrderProduct::find()->where(['order_id'=>$oldOrder->id,'type'=>'service'])->orderBy('id')->all();
            foreach ($oldItems as $oldItem) {
//echo $oldItem->name."\n";
                $newItem=new OrderItem();
                $newItem->id=$oldItem->id;
                $newItem->order_id=$oldItem->order_id;
                $newItem->name=$oldItem->name;
                $newItem->qty=$oldItem->qty;
                $newItem->price=$oldItem->cost;
                $newItem->note=$oldItem->comment;
                $newItem->service_id=$oldItem->service_id;
                $newItem->current_status=$status_id;
                $newItem->type_id=OrderItem::TYPE_SERVICE;
                $newItem->save();
            }

//          Проверяем общую стоимость заказа
            if ($newOrder->getTotalCost()!=$oldOrder->getSumm()) {
                echo "Стомость заказов не сходится. Заказ №:".$newOrder->id."\n";
            }
            $num++;


//            if ($num==5) break;

        }
        return $num;
    }

    private function importBlock($client_id):int
    {
        if (!$client = Client::findOne($client_id)) return false;
        if (!$site_id = $client->getFirstSite()->id) return false;
        Yii::$app->params['siteId'] = $site_id;
        //очищаем
        if ($newBlocks = ItemBlock::find()->andWhere(['site_id' => $site_id])->all()) {
            foreach ($newBlocks as $newBlock) {
                $newBlock->delete();
            }
        }
        $oldBlocks=\common\models\Block::find()->all();
        $num=0;
        /** @var \common\models\Block $oldBlock */
        foreach ($oldBlocks as $oldBlock) {
            $newBlock=ItemBlock::create($oldBlock->name);
            $newBlock->id=$oldBlock->id;
            if ($newBlock->save()) {
                $num++;
            }
        }
        return $num;
    }
}