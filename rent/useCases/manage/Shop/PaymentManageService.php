<?php


namespace rent\useCases\manage\Shop;


use rent\entities\Shop\Order\CustomerData;
use rent\entities\Shop\Order\Payment;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\repositories\Shop\PaymentRepository;
use rent\services\TransactionManager;
use Yii;

class PaymentManageService
{
    private $payments;
    private $transaction;

    public function __construct(PaymentRepository $payments,TransactionManager $transaction)
    {
        $this->payments = $payments;
        $this->transaction = $transaction;
    }
    public function create(PaymentForm $form): Payment
    {
        $payment = Payment::create(
            (int)$form->dateTime,
            (int)$form->type_id,
            (float)$form->sum,
            $form->responsible_id,
            $form->responsible_name,
            $form->payer_id,
            new CustomerData(
                $form->payer->phone,
                $form->payer->name,
                $form->payer->email
            ),
            $form->purpose_id,
            $form->note
        );
        //Если корректировка, тогда деактивируем все движении ранее этой даты Убрал
        if ($payment->isCorrect()) {
            $this->transaction->wrap(function () use ($payment) {
                $this->payments->save($payment);
//                $connection = Yii::$app->db;
//                $connection->createCommand()->update(Payment::tableName(), ['active'=>0], 'dateTime < :dateTime', [':dateTime' => $payment->dateTime])->execute();
            });
        } else {
            $this->payments->save($payment);
        }

        return $payment;
    }
    private function deactivateBeforeDate(int $date):int
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->update(Payment::tableName(), ['active'=>0], 'dateTime < '. $date)->execute();
    }
}