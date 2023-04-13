<?php

namespace rent\entities\Support\Task;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use rent\entities\behaviors\ClientBehavior;
use rent\entities\Support\Task\Comment;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\FileUploadBehavior;

/**
 * @property integer $id
 * @property string $file
 * @property string $task_id
 * @property string $comment_id
 *
 * @mixin FileUploadBehavior
 */
class File extends ActiveRecord
{

    public static function create(UploadedFile $uploadedFile): self
    {
        $file = new static();
        $file->file = $uploadedFile;
        return $file;
    }

    public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public static function tableName(): string
    {
        return '{{%support_task_files}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => FileUploadBehavior::class,
                'attribute' => 'file',
                'filePath' => '@staticRoot/support/tasks/[[id_path]]/[[filename]].[[extension]]',
                'fileUrl' => '@static/support/tasks/[[id_path]]/[[filename]].[[extension]]'
            ],
            ClientBehavior::class,
            TimestampBehavior::class,
            'SaveRelationsBehavior'=>
                [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'files'
                    ],
                ],
        ];
    }
    public function getUrl():string
    {
        return $this->resolvePath($this->fileUrl);
    }
}