<?php

namespace rent\entities\Client;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use rent\services\WaterMarker;

/**
 * @property integer $id
 * @property string $file
 *
 * @mixin ImageUploadBehavior
 */
class File extends ActiveRecord
{
    public static function create(UploadedFile $uploadedFile): self
    {
        $file = new static();
        $file->file = $uploadedFile;
        return $file;
    }

    public function updateFile($file)
    {
        $this->file=$file;
    }


     public function isIdEqualTo($id): bool
    {
        return $this->id == $id;
    }

    public static function tableName(): string
    {
        return '{{%client_files}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'file',
                'createThumbsOnRequest' => true,
                'filePath' => '@staticRoot/origin/sites/[[id_path]]/[[id]].[[extension]]',
                'fileUrl' => '@static/origin/sites/[[id_path]]/[[id]].[[extension]]',
                'thumbPath' => '@staticRoot/cache/sites/[[id_path]]/[[profile]]_[[id]].[[extension]]',
                'thumbUrl' => '@static/cache/sites/[[id_path]]/[[profile]]_[[id]].[[extension]]',
                'thumbs' => [
                    'logo_100x25' => ['width' => 100, 'height' => 25],
                    'logo_153x36' => ['width' => 153, 'height' => 36],
                ],
            ],
        ];
    }

    /**
     * Replaces all placeholders in path variable with corresponding values
     *
     * @param string $path
     * @return string
     */
    public function resolvePath($path)
    {
//        $path = $this->resolvePath($path);
        return $path;

    }
}