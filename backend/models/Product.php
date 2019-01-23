<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product as ProductModel;

/**
 * Product represents the model behind the search form of `\common\models\Product`.
 */
class Product extends ProductModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'priceType_id', 'client_id'], 'integer'],
            [['name', 'description', 'tag', 'cod', 'is_active','productType'], 'safe'],
            [['priceRent', 'priceSelling','pricePrime'], 'number'],
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
        $query = ProductModel::find();

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
            'id' => $this->id,
            'priceRent' => $this->priceRent,
            'priceSelling' => $this->priceSelling,
            'pricePrime' => $this->pricePrime,
            'client_id' => $this->client_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tag', $this->tag])
            ->andFilterWhere(['like', 'cod', $this->cod])
            ->andFilterWhere(['like', 'is_active', $this->is_active])
            ->andFilterWhere(['like', 'productType', $this->productType]);

        return $dataProvider;
    }
}
