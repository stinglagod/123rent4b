<?php

namespace rent\entities\Shop\Order\Sketch;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yiidreamteam\upload\FileUploadBehavior;

class Sketch extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $file;

    public static function tableName()
    {
        return 'order_sketch_files';
     }

    public function behaviors()
    {
        return [
            [
                'class' => FileUploadBehavior::class,
                'attribute' => 'file',
                'filePath' => '@staticRoot/order/sketches/[[id]].[[extension]]',
                'fileUrl' => '@static/order/sketches/[[id]].[[extension]]',
            ],
        ];
    }

    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }
}
