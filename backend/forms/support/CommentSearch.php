<?php

namespace backend\forms\support;

use rent\entities\Support\Task\Comment;
use rent\entities\Support\Task\Task;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CommentSearch extends Model
{
    public ?int $authorId=null;
    public ?string $message=null;
    private int $taskId;

    public function __construct(int $taskId, $config = [])
    {
        parent::__construct($config);
        $this->taskId = $taskId;
    }

    public function rules(): array
    {
        return [
            [['authorId'], 'integer'],
            [['message'], 'safe'],
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Comment::find()->where(['task_id'=>$this->taskId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_ASC]
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'author_id' => $this->authorId,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
