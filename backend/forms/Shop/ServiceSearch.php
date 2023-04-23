<?php

namespace backend\forms\Shop;

use rent\entities\Shop\Service;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ServiceSearch extends Model
{
    public ?int $id=null;
    public ?string $name=null;
    public ?float $percent=null;
    public ?float $defaultCost=null;
    public ?int $status=null;

    public function rules(): array
    {
        return [
            [['id','status'], 'integer'],
            [['percent','defaultCost'], 'double'],
            [['name'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Service::find();

        if (!\Yii::$app->user->can('super_admin')) {
            $query->andWhere(['!=','status',Service::STATUS_DELETED]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status'=>$this->status
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['percent'=>$this->percent]);
        $query->andFilterWhere(['defaultCost'=>$this->defaultCost]);

        return $dataProvider;
    }
}
