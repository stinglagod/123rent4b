<?php


namespace rent\useCases\manage\Shop;


use rent\entities\Shop\Order\CustomerData;
use rent\entities\Shop\Order\Payment;
use rent\forms\manage\Shop\Order\PaymentForm;
use rent\repositories\Shop\PaymentRepository;

class PaymentManageService
{
    private $payments;

    public function __construct(PaymentRepository $payments)
    {
        $this->payments = $payments;
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
}