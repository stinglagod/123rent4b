<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderProductAction;

/**
 * OrderProductActionSearch represents the model behind the search form of `\common\models\OrderProductAction`.
 */
class OrderProductActionSearch extends OrderProductAction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_product_id', 'movement_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = OrderProductAction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_product_id' => $this->order_product_id,
            'movement_id' => $this->movement_id,
        ]);

        return $dataProvider;
    }
}
