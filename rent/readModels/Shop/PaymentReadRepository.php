<?php

namespace rent\readModels\Shop;


use rent\entities\Shop\Order\Payment;

class PaymentReadRepository
{
    public function find($id): ?Payment
    {
        return Payment::findOne($id);
    }

    public function balanceByDate(int $date):?float
    {
        //Сначала надо найти когда была последняя Корректировка.
        //-----------------|корректировка|+++++++++++++
        //считаем сумму корректировки + все движения, что справа
        $sum=0;
        $begin=0;
        if ($paymentCorrect=$this->findLastCorrect()) {
            $begin=$paymentCorrect->dateTime;
            $sum+=$paymentCorrect->sum;
        }
        $currentSum=Payment::find()->where(['active'=>1])
            ->andWhere(['>','dateTime',$begin])
            ->andWhere(['<=','dateTime',$date])
            ->sum('sum');
        if ($currentSum) {
            $sum+=$currentSum;
        }
        return $sum;
    }

    public function findLastCorrect()
    {
        return Payment::find()->where(['active'=>1,'purpose_id'=>Payment::POP_CORRECT])->orderBy('dateTime DESC')->one();
    }
}