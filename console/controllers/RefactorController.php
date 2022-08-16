<?php
namespace console\controllers;

use common\models\Cash;
use common\models\OrderBlock;
use common\models\OrderProduct;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\CashFlow\Constant\Periodic\Payments;
use rent\cart\CartItem;
use rent\entities\Client\Site;
use rent\entities\Shop\Category\Category;
use rent\entities\Shop\Order\BalanceCash;
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
use rent\forms\manage\Shop\Order\PayerForm;
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
            /** @var \rent\entities\Shop\Category\Category $category */
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
            //Заказы
            $num=0;
            $orders=Order::find(true)->all();
            foreach ($orders as $order) {
                if (empty($order->client_id)) {
                    $site=Site::find(true)->where(['id'=>$order->site_id])->one();
                    $order->client_id = $site->client_id;
                    if ($order->save()) {
                        $num++;
                    }
                }
            }
            echo "Кол-во заказов с установкой клиента: $num \n";
            //Оплаты
            $num=0;
            $payments=Payment::find(true)->all();
            foreach ($payments as $payment) {
                if (empty($payment->client_id)) {
                    $site=Site::find(true)->where(['id'=>$payment->site_id])->one();
                    $payment->client_id = $site->client_id;
                    if ($payment->save()) {
                        $num++;
                    }
                }
            }
            echo "Кол-во оплат с установкой клиента: $num \n";
            //Баланс денег
            $num=0;
            $entities=BalanceCash::find()->all();
            foreach ($entities as $entity) {
                if (empty($entity->client_id)) {
                    $site=Site::find(true)->where(['id'=>$entity->site_id])->one();
                    $entity->client_id = $site->client_id;
                    if ($entity->save()) {
                        $num++;
                    }
                }
            }
            echo "Кол-во Баланса денег с установкой клиента: $num \n";
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

    /**
     * Из-за ошибки в коде не верно сохранялась информация о плательщике. payments и ответственном
     * 1. Вместо имени записывался телефон, и наоборот.
     * 2. не заполнялось responsible_id и responsible_name.
     * Заполняем. Исправляем. Запускать 1 раз, потом этот метод убираем
     */
    public function actionPaymentNameToPhone()
    {
        $payments=Payment::find(true)->all();
//        dump($payments);exit;
        $count=0;
        /** @var Payment $payment */
        foreach ($payments as $payment) {
//            echo $payment->id . PHP_EOL;
            echo $payment->id ;
            $payment->payerData=new CustomerData($payment->payer_name,$payment->payer_phone,$payment->payer_email);

            if (($payment->author_id)and(empty($payment->responsible_id))) {
                $payment->responsible_id=$payment->author_id;
//                echo $payment->author_id . ' ' . $payment->author->name ;
                $payment->responsible_name=$payment->author->getShortName();
            }
            echo PHP_EOL;
            $payment->save();
            $count++;
        }

        dump($count);
    }

################################################################
//    private function

    private function updateSettings($client_id):void
    {
        if (!$client=Client::findOne($client_id)) throw new \DomainException('Don not find client');
        Yii::$app->settings->initClient($client->id);
    }
}