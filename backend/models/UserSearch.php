<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use rent\entities\User\User;

/**
 * UserSearch represents the model behind the search form of `\rent\entities\User\User`.
 */
class UserSearch extends \rent\entities\User\User
{
    public $dateCreate_from;
    public $dateCreate_to;
    public $dateUpdate_from;
    public $dateUpdate_to;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['auth_key', 'password_hash', 'password_reset_token', 'email'], 'safe'],
            [['dateCreate_from', 'dateCreate_to','dateUpdate_from', 'dateUpdate_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = \rent\entities\User\User::find();

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);

        $query->andFilterWhere(['>=', 'created_at', $this->dateCreate_from ? strtotime($this->dateCreate_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->dateCreate_to ? strtotime($this->dateCreate_to . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'updated_at', $this->dateUpdate_from ? strtotime($this->dateUpdate_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'updated_at', $this->dateUpdate_to ? strtotime($this->dateUpdate_to . ' 23:59:59') : null])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname]);


        return $dataProvider;
    }
}
