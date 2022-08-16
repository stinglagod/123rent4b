<?php

namespace backend\forms\Shop;

use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Payment;
use rent\entities\Shop\Order\Status;
use rent\helpers\OrderHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

class PaymentSearch extends Model
{
    public ?int $id=null;
    public ?int $date_from=null;
    public ?int $date_to=null;
    public ?float $sum=null;
    public ?int $authorId=null;
    public ?int $responsible_id=null;
    public ?string $note=null;
    public ?string $payer_name=null;
    public ?string $payer_phone=null;

    public function rules(): array
    {
        return [
            [['id','responsible_id'], 'integer'],
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
                'defaultOrder' => ['dateTime' => SORT_ASC]
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

        $query->andFilterWhere([
            'o.id' => $this->id,
            'o.responsible_id' => $this->responsible_id,
            'o.sum' => $this->sum,
        ]);

        $query
            ->andFilterWhere(['like', 'o.note', $this->note])
            ->andFilterWhere(['like', 'o.payer_name', $this->payer_name])
            ->andFilterWhere(['like', 'o.payer_phone', $this->payer_phone])
            ->andFilterWhere(['>=', 'o.dateTime', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'o.dateTime', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}