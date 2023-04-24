<?php

namespace backend\forms\support;

use rent\entities\Support\Task\Task;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class TaskSearch extends Model
{
    public $id;
    public $name;
    public $text;
    public $type;
    public $status;

    public function rules(): array
    {
        return [
            [['id','type','status'], 'integer'],
            [['name'], 'safe'],
            ['type', 'in', 'range' => array_keys($this->getTypeList())],
            ['status', 'in', 'range' => array_keys($this->getStatusList())],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Task::find();

//        if (\Yii::$app->user->can('super_admin')) {
//
//        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text]);
//Status
        $this->status=(empty($this->status))?-1:$this->status;
        if ($this->status==-1) {
            $query->andFilterWhere(['<>','status', [Task::STATUS_CLOSED, Task::STATUS_DELETED]]);
        } else if ($this->status!=-2) {
            $query->andFilterWhere(['status' => $this->status]);
        }
//Type
        $this->type=(empty($this->type))?-2:$this->type;
        if ($this->type==-1) {
//            $query->andFilterWhere(['<>','type', [Task::STATUS_CLOSED, Task::STATUS_DELETED]]);
        } else if ($this->type!=-2) {
            $query->andFilterWhere(['type' => $this->type]);
        }

        return $dataProvider;
    }

    public function getStatusList():array
    {
        return ArrayHelper::merge(
            [
                '-1' => "Скрыть закрытые",
                '-2' => "Показать все"
            ],
            Task::getStatusLabels()
        );
    }

    public function getTypeList():array
    {
        return ArrayHelper::merge(
            [
//                '-1' => "Скрыть закрытые",
                '-2' => "Показать все"
            ],
            Task::getTypeLabels()
        );
    }
}
