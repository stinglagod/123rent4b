<?php
namespace console\controllers;

use rent\entities\CRM\Contact;
use rent\entities\Shop\Order\Order;
use rent\entities\Client\Client;
use rent\entities\Shop\Order\Payment;
use rent\forms\manage\CRM\ContactForm;
use rent\readModels\Shop\OrderReadRepository;
use rent\repositories\CRM\ContactRepository;
use rent\useCases\manage\CRM\ContactManageService;
use rent\useCases\manage\Shop\OrderManageService;
use Yii;
use yii\console\Controller;

class RefactorController extends Controller
{

    private OrderManageService $service;
    private OrderReadRepository $orders;
    private ContactRepository $contacts;
    private ContactManageService $contactService;

    public function __construct(
        $id,
        $module,
        OrderManageService $service,
        OrderReadRepository $orders,
        ContactRepository $contacts,
        ContactManageService $contactService,
        $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->orders = $orders;
        $this->contacts = $contacts;
        $this->contactService = $contactService;
    }

    /**
     * Перевод customer to contact_id
     */
    public function actionCustomerToContact()
    {
        $orders=Order::find(true)->all();
        $n=0;
        $new=0;
        $empty=0;
        /** @var Order $order */
        foreach ($orders as $order) {
            if (
                empty($order->customerData->name) and
                empty($order->customerData->phone) and
                empty($order->customerData->email)
            ) {
                $empty++;
                continue;
            }

            if (!$contact=$this->contacts->findByNamePhoneEmail($order->customerData->name,$order->customerData->phone,$order->customerData->email,true))
            {
                $contact=new Contact();
                $contact->telephone=$order->customerData->phone;
                $contact->email=$order->customerData->email;
                $contact->name=$order->customerData->name;
                $contact->client_id=$order->client_id;
                $contact->status=Contact::STATUS_ACTIVE;;
                $this->contacts->save($contact);
                $new++;
            }

            $order->contact_id=$contact->id;
            $order->save();
            $n++;
            echo "Заказ № ". $order->id . PHP_EOL;
        }
        echo "Обработано: $n заказов. Добавлено $new контактов. Пропущено $empty заказов (заказчик пустой)" . PHP_EOL;
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
     * Пересохранение движение Д/С
     */
    public function actionReSavePayments($client_id)
    {
        $this->updateSettings($client_id);
        $payments=Payment::find()->all();
        /** @var Order $order */
        foreach ($payments as $payment) {
            $payment->save();
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