<?php

namespace backend\forms\Client;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use rent\entities\Client\Client;

/**
 * ClientSearch represents the model behind the search form of `\rent\entities\Client\Client`.
 */
class ClientSearch extends \rent\entities\Client\Client
{
    public $id;
    public $date_from;
    public $date_to;
    public $name;

    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name'], 'safe'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
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
        $query = Client::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
