<?php

namespace rent\entities\Support\Task;

use rent\entities\behaviors\ClientBehavior;
use rent\entities\Client\Client;
use rent\entities\User\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
/**
* @property integer $id
* @property string $comment                         //Комментарий
* @property integer $task_id
*
* @property integer $created_at
* @property integer $updated_at
* @property integer $author_id
* @property string $author_name
* @property integer $lastChangeUser_id
*
*/
class Comment extends ActiveRecord
{
    public static function create(string $comment, User $author):self
    {
        return new self([
            'comment'=>$comment,
            'author_id'=>$author->id,
            'author_name'=>$author->getShortName(),
        ]);
    }
    public static function tableName(): string
    {
        return '{{%support_tasks_comment}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class
        ];
    }
}