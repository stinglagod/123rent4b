<?php


namespace rent\useCases\manage\Shop;


use rent\entities\Shop\Order\CustomerData;
use rent\entities\Shop\Order\Payment;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\repositories\Shop\PaymentRepository;
use rent\services\export\PaymentExportService;
use rent\services\TransactionManager;
use Yii;

class PaymentManageService
{
    private PaymentRepository $payments;
    private TransactionManager $transaction;
    private PaymentExportService $export;

    public function __construct(PaymentRepository $payments,TransactionManager $transaction,PaymentExportService $export)
    {
        $this->payments = $payments;
        $this->transaction = $transaction;
        $this->export = $export;
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

        $this->payments->save($payment);
        return $payment;
    }
    public function edit($id,PaymentForm $form): void
    {
        $payment = $this->payments->get($id);
        $payment->edit(
            $form->dateTime,
            $form->type_id,
            $form->sum,
            $form->responsible_id,
            $form->responsible_name,
            $form->payer_id,
            new CustomerData($form->payer->phone,$form->payer->name,$form->payer->email),
            $form->purpose_id,
            $form->note,
            1
        );
        $this->payments->save($payment);
    }
    private function deactivateBeforeDate(int $date):int
    {
        $connection = Yii::$app->db;
        $connection->createCommand()->update(Payment::tableName(), ['active'=>0], 'dateTime < '. $date)->execute();
    }

###Export
    public function exportPayments($dataProvider,array $balances):string
    {
        return $this->export->exportToExcel($dataProvider,$balances);
    }
}