<?php

namespace backend\models;

use common\models\Status;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `\common\models\Order`.
 */
class OrderSearch extends Order
{
    public $responsibleName;
//    public $paidStatusName;
    public $owner;
    public $hideClose;
    public $hidePaid;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'autor_id', 'lastChangeUser_id', 'client_id','responsible_id','status_id','statusPaid_id'], 'integer'],
            [['cod', 'is_active'], 'safe'],
            [['created_at','updated_at', 'dateBegin','dateEnd'], 'date', 'format' => 'php:Y-m-d'],
            [['name'],'string'],
            [['responsibleName','owner','hideClose','hidePaid'],'safe']
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
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 200
            ],
        ]);

//        $dataProvider->setSort([
//            'attributes' => [
//                'id',
//                'dateBegin',
//                'name',
//                'fullName' => [
//                    'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
//                    'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
//                    'label' => 'Full Name',
//                    'default' => SORT_ASC
//                ],
//                'paidStatusName' => [
//                    'asc' => ['user.name' => SORT_ASC],
//                    'desc' => ['user.name' => SORT_DESC],
//                    'label' => 'Менеджер1'
//                ]
//            ]
//        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'autor_id' => $this->autor_id,
            'lastChangeUser_id' => $this->lastChangeUser_id,
            'client_id' => $this->client_id,
            'responsible_id' => $this->responsible_id,
            'status_id' => $this->status_id,
            'statusPaid_id' => $this->statusPaid_id,
        ]);
        if ($this->owner) {
            $query->andFilterWhere(['responsible_id' => Yii::$app->user->id]);
        }
        if ($this->hideClose) {
            $query->andFilterWhere(['<>','status_id', Status::CLOSE])
                ->andFilterWhere(['<>','status_id', Status::CANCELORDER]);
        }
        if ($this->dateBegin) {
            $query->andFilterWhere(['<=','dateBegin',$this->dateBegin]);
        }
        if ($this->hidePaid) {
            $query->andFilterWhere(['<>','statusPaid_id', Order::FULLPAID]);
        }

        $query->andFilterWhere(['like', 'cod', $this->cod])
            ->andFilterWhere(['like', 'is_active', $this->is_active]);
        $query->andFilterWhere(['like','name',$this->name]);

        $query->orderBy('dateBegin');

        return $dataProvider;
    }
}
