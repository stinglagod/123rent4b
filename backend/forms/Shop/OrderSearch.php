<?php

namespace backend\forms\Shop;

use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use rent\entities\Shop\Order\Status;
use rent\helpers\OrderHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

class OrderSearch extends Model
{
    public $id;
    public $date_from;
    public $date_to;
    public $status;
    public $note;
    public $name;
    public $responsible_id;
    public $current_status;

    public function rules(): array
    {
        return [
            [['id','status','responsible_id','current_status'], 'integer'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
            [['note','name'],'string'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Order::find()->alias('o');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date_begin' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'o.id' => $this->id,
            'o.current_status' => $this->status,
        ]);

        $query
            ->andFilterWhere(['like', 'o.note', $this->note])
            ->andFilterWhere(['like', 'o.name', $this->name])
            ->andFilterWhere(['>=', 'o.date_begin', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'o.date_begin', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        // По умолчанию показать мои
        $this->responsible_id=(empty($this->responsible_id))?-2:$this->responsible_id;
        if ($this->responsible_id==-1) {
            $query->andFilterWhere(['responsible_id' => Yii::$app->user->id]);
        } else if ($this->responsible_id!=-2) {
            $query->andFilterWhere(['responsible_id' => $this->responsible_id]);
        }
        // По умолчанию статус показать все
        $this->current_status=(empty($this->current_status))?-1:$this->current_status;
        if ($this->current_status==-1) {
            $query->andFilterWhere(['<>','current_status', Status::CANCELLED]);
            $query->andFilterWhere(['<>','current_status', Status::COMPLETED]);
        } else if ($this->current_status!=-2) {
            $query->andFilterWhere(['current_status' => $this->current_status]);
        }
//        // По умолчанию статус оплаты скрыть оплаченные
//        $this->statusPaid_id=(empty($this->statusPaid_id))?-2:$this->statusPaid_id;
//        if ($this->statusPaid_id==-1) {
//            $this->hidePaid=1;
//            $query->andFilterWhere(['<>','statusPaid_id', Order::FULLPAID]);
//        } else if ($this->statusPaid_id!=-2) {
//            $query->andFilterWhere(['statusPaid_id' => $this->statusPaid_id]);
//        }

        return $dataProvider;
    }

    public function statusList(): array
    {
        $arr=[
            '-1' => "Скрыть закрытые",
            '-2' => "Показать все"
        ];
        return $arr+OrderHelper::statusList();
    }
}
