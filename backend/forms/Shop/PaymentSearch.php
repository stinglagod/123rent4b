<?php

namespace backend\forms\Shop;

use rent\helpers\DateHelper;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use rent\entities\Shop\Order\Status;
use rent\helpers\OrderHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

class PaymentSearch extends Model
{
    public  $id;
    public  $date_from;
    public  $date_to;
    public  $sum;
    public  $authorId;
    public  $responsible_id;
    public  $note;
    public  $payer_name;
    public  $payer_phone;
    public  $type_id;

    public function rules(): array
    {
        return [
            [['id','responsible_id','type_id'], 'integer'],
            [['sum'], 'double'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            [['note','payer_name','payer_phone'],'string'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Payment::find()->alias('o');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['dateTime' => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 100
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        if (empty($this->date_from)) {
            $dateFromUnix=DateHelper::beginMonthDayByUnixTime(time());
            $this->date_from=date('Y-m-d',$dateFromUnix);

        } else {
            $dateFromUnix=strtotime($this->date_from . ' 00:00:00');
        }
        if (empty($this->date_to)) {
            $dateToUnix=DateHelper::lastMonthDayByUnixTime(time());
            $this->date_to=date('Y-m-d',$dateToUnix);
        } else {
            $dateToUnix=strtotime($this->date_to . ' 23:59:59');
        }

        $query->andFilterWhere([
            'o.id' => $this->id,
            'o.sum' => $this->sum,
            'o.type_id' => $this->type_id,
        ]);

        $query
            ->andFilterWhere(['like', 'o.note', $this->note])
            ->andFilterWhere(['like', 'o.payer_name', $this->payer_name])
            ->andFilterWhere(['like', 'o.payer_phone', $this->payer_phone])
            ->andFilterWhere(['>=', 'o.dateTime', $dateFromUnix ])
            ->andFilterWhere(['<=', 'o.dateTime', $dateToUnix ]);

        // По умолчанию показать мои
        $this->responsible_id=(empty($this->responsible_id))?-2:$this->responsible_id;
        if ($this->responsible_id==-1) {
            $query->andFilterWhere(['responsible_id' => Yii::$app->user->id]);
        } else if ($this->responsible_id!=-2) {
            $query->andFilterWhere(['responsible_id' => $this->responsible_id]);
        }

        return $dataProvider;
    }
}