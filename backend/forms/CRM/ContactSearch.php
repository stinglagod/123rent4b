<?php

namespace backend\forms\CRM;

use rent\entities\CRM\Contact;
use rent\helpers\ContactHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
* @property string $id
* @property string $name
* @property string $surname
* @property string $patronymic
* @property string $status
* @property string $telephone
* @property string $email
* @property string $note
**/
class ContactSearch extends Model
{
    public ?string $id=null;
    public ?string $name=null;
    public ?string $surname=null;
    public ?string $patronymic=null;
    public ?string $status=null;
    public ?string $telephone=null;
    public ?string $email=null;
    public ?string $note=null;

    public $date_from;
    public $date_to;

    public function rules(): array
    {
        return [
            [['id','status'], 'integer'],
            ['status', 'in', 'range' => [
                Contact::STATUS_ACTIVE,
                Contact::STATUS_DELETED,
                Contact::STATUS_NOT_ACTIVE
            ]],
            [['name', 'surname', 'patronymic', 'telephone', 'email', 'note'], 'string'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Contact::find();

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
        ]);

        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'surname', $this->patronymic])
            ->andFilterWhere(['like', 'surname', $this->email])
            ->andFilterWhere(['like', 'surname', $this->telephone])
            ->andFilterWhere(['like', 'surname', $this->note])
            ->andFilterWhere(['>=', 'created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        return $dataProvider;
    }

    public function statusList(): array
    {
        $arr=[
//            '-1' => "Скрыть закрытые",
//            '-2' => "Показать все"
        ];
        return $arr+ContactHelper::statusList();
    }
}
