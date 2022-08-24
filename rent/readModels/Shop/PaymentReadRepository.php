<?php

namespace rent\readModels\Shop;


use rent\entities\Shop\Order\Payment;
use rent\helpers\PaymentHelper;

class PaymentReadRepository
{
    public function find($id): ?Payment
    {
        return Payment::findOne($id);
    }

    public function balanceByDate(int $date,int $type_id=null):?float
    {
        //Сначала надо найти когда была последняя Корректировка.
        //-----------------|корректировка|+++++++++++++
        //считаем сумму корректировки + все движения, что справа
        $sum=0;
        $begin=0;
        /** @var Payment $paymentCorrect */
        if ($paymentCorrect=$this->findLastCorrect($type_id)) {
            $begin=$paymentCorrect->dateTime;
            $sum+=$paymentCorrect->sumWithSign;
        }
        $currentSum=Payment::find()->where(['active'=>1])
            ->andWhere(['>','dateTime',$begin])
            ->andWhere(['<=','dateTime',$date]);

        if ($type_id){
            if ($type_id==-1) {
                $currentSum->andWhere(['type_id'=>0]);
            } else {
                $currentSum->andWhere(['type_id'=>$type_id]);
            }

        }

        $currentSum=$currentSum->sum('sumWithSign');

        if ($currentSum) {
            $sum+=$currentSum;
        }
        return $sum;
    }

    public function balancesByDate(int $date):array
    {
        $balances=[];
        $sum=0;
        $balances['null']=$this->balanceByDate($date,-1);
        $sum+=$balances['null'];
        foreach (PaymentHelper::paymentTypeList() as $type_id=>$item)
        {
            $balances[$type_id]=$this->balanceByDate($date,$type_id);
            $sum+=$balances[$type_id];
        }
        $balances['all']=$sum;

        return $balances;
    }
    public function findLastCorrect(int $type_id=null)
    {
        $query=Payment::find()->where(['active'=>1,'purpose_id'=>Payment::POP_CORRECT]);
        if ($type_id){
            $query->andWhere(['type_id'=>$type_id]);
        }
        return $query->orderBy('dateTime DESC')->one();
    }
}