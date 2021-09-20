<?php
namespace console\controllers;

use common\models\Cash;
use common\models\OrderBlock;
use common\models\OrderProduct;
use rent\cart\CartItem;
use rent\entities\Client\Site;
use rent\entities\Shop\Category;
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
use rent\entities\User\User;
use rent\forms\manage\Shop\Order\OrderCreateForm;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\forms\manage\Shop\Product\PhotosForm;
use rent\readModels\Shop\OrderReadRepository;
use rent\repositories\Shop\CharacteristicRepository;
use rent\useCases\manage\Shop\OrderManageService;
use rent\useCases\manage\Shop\ProductManageService;
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
     * Перевод учета с учету по сайту к учету по клиенту. Это касается каталога, товаров и т.д.
     */
    public function actionSiteIdToClientId()
    {
        $clients=Client::find()->all();
        foreach ($clients as $client) {
            echo "Клиент: $client->name \n";
            $this->updateSettings($client->id);
            //каталог
            $categories=Category::find()->all();
            $num=0;
            /** @var Category $category */
            foreach ($categories as $category) {
                if (empty($category->client_id)) {
                    $category->client_id=$category->site->client_id;
                    if ($category->save()) {
                        $num++;
                    }
                }
            }
            echo "Кол-во категоирй с установкой клиента: $num \n";
            //товары
            $num=0;
            $products=Product::find()->all();
            foreach ($products as $product) {
                if (empty($product->client_id)) {
                    $product->client_id = $product->site->client_id;
                    if ($product->save()) {
                        $num++;
                    }
                }
            }
            echo "Кол-во товаров с установкой клиента: $num \n";
            //теги
            $num=0;
            $tags=Tag::find()->all();
            foreach ($tags as $tag) {
                if (empty($tag->client_id)) {
                    $tag->client_id = $tag->site->client_id;
                    if ($tag->save()) {
                        $num++;
                    }
                }
            }
            echo "Кол-во тегов с установкой клиента: $num \n";
            //Характеристики
            $num=0;
            $characteristics=Characteristic::find(true)->all();
            foreach ($characteristics as $characteristic) {
                if (empty($characteristic->client_id)) {
                    $site=Site::find(true)->where(['id'=>$characteristic->site_id])->one();
                    $characteristic->client_id = $site->client_id;
                    if ($characteristic->save()) {
                        $num++;
                    }
                }
            }
            echo "Кол-во Характеристик с установкой клиента: $num \n";
        }

    }
    /**
     * Перепроведение заказов
     */
    public function actionReSaveOrders($client_id)
    {
        $this->updateSettings($client_id);
        $orders=Order::find()->all();
        /** @var Order $order */
        foreach ($orders as $order) {
            $order->updatePaidStatus();
            $order->save();
        };
    }

################################################################
//    private function

    private function updateSettings($client_id):void
    {
        if (!$client=Client::findOne($client_id)) throw new \DomainException('Don not find client');
        Yii::$app->settings->initClient($client->id);
    }
}