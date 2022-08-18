<?php

namespace rent\helpers;

use rent\entities\Shop\Order\Payment;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PaymentHelper
{
    public static function paymentTypeList(): array
    {
        return [
            Payment::TYPE_BY_CARD           => 'Банковская карта',
            Payment::TYPE_CASH              => 'Наличные',
            Payment::TYPE_TO_BANK_ACCOUNT   => 'Расчетный счет',
        ];
    }

    public static function paymentTypeName($payment_id): string
    {
        return ArrayHelper::getValue(self::paymentTypeList(), $payment_id);
    }


    public static function paymentPurposeList(): array
    {
        return [
            Payment::POP_INCOMING           => 'Приход д/c',
            Payment::POP_ADVANCE            => 'Аванс',
            Payment::POP_DEPOSIT            => 'Залог(депозит)',
            Payment::POP_REFUND             => 'Возрат д/c',
            Payment::POP_CONTRACTOR         => 'Оплата контрагенту',
            Payment::POP_CORRECT         => 'Корректировка',
        ];
    }

    public static function paymentPurposeName($payment_id): string
    {
        return ArrayHelper::getValue(self::paymentPurposeList(), $payment_id);
    }

    public static function getSumHtml(Payment $payment):string
    {
        if ($payment->isPlus()) {
            return '<span class="glyphicon glyphicon-arrow-up text-green">&nbsp;'.Html::encode($payment->sum).'</span>';
        } else {
            return '<span class="glyphicon glyphicon-arrow-down text-red">&nbsp;'.Html::encode($payment->sum*-1).'</span>';
        }

    }

    public static function getSum(Payment $payment):string
    {
        if ($payment->isPlus()) {
            return $payment->sum;
        } else {
            return $payment->sum*-1;
        }

    }

    public static function getTypeIconHtml($type_id):string
    {
        switch ($type_id) {
            case Payment::TYPE_BY_CARD:
                $class = 'fa fa-credit-card';
                break;
            case Payment::TYPE_CASH:
                $class = 'fa fa-money';
                break;
            case Payment::TYPE_TO_BANK_ACCOUNT:
                $class = 'fa fa-university';
                break;
//            case Payment::TYPE_CORRECT:
//                $class = 'fa fa-calculator';
//                break;
            default:
                $class = '';
        }

        return Html::tag('span', '', [
            'class' => $class,
            'title' => ArrayHelper::getValue(self::paymentTypeList(), $type_id)
        ]);
    }

}