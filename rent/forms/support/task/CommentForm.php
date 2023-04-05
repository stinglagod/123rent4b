<?php

namespace rent\forms\support\task;

use yii\base\Model;

class CommentForm extends Model
{
    public function rules(): array
    {
        return [
            [['comment'], 'text'],
            [['created_at','updated_at','author_id','lastChangeUser_id'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'comment' => 'Сообщение',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
            'author_id' => 'Автор',
            'lastChangeUser_id' => 'Последний изменения'
        ];
    }
}