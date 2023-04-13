<?php

namespace rent\entities\Support\Task;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\User\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
* @property integer $id
* @property string $message                         //Сообщение
* @property integer $task_id
*
* @property integer $created_at
* @property integer $updated_at
* @property integer $author_id
* @property string $author_name
* @property integer $lastChangeUser_id
* @property string $lastChangeUser_name
*
* @property User $author
* @property Task $task
* @property File[] $files
*
*/
class Comment extends ActiveRecord
{
    public static function create(string $message, User $author):self
    {
        return new self([
            'message'=>$message,
            'author_id'=>$author->id,
            'author_name'=>$author->getShortName(),
        ]);
    }

    #Files
    public function addFile(UploadedFile $file): void
    {
        $files = $this->files;
        $files[] = File::create($file);
        $this->files=$files;
    }
    public function removeFile($id): void
    {
        $files = $this->files;
        foreach ($files as $i => $file) {
            if ($file->isIdEqualTo($id)) {
                unset($files[$i]);
                $this->files=$files;
                return;
            }
        }
        throw new \DomainException('File is not found.');
    }

    public static function tableName(): string
    {
        return '{{%support_task_comments}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            ClientBehavior::class,
            'SaveRelationsBehavior'=>
                [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'files'
                    ],
                ],
        ];
    }
    public function itIsMy(?int $userId=null):bool
    {
        $userId=$userId??\Yii::$app->user->getId();
        return $this->author_id==$userId;
    }

    public function getAuthor() :ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getTask() :ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
    public function getFiles():ActiveQuery
    {
        return $this->hasMany(File::class, ['comment_id' => 'id']);
    }

}