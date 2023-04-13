<?php

namespace rent\forms\support\task;

use yii\base\Model;
use yii\web\UploadedFile;

class FilesForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules(): array
    {
        return [
//            ['files', 'each', 'rule' => ['image']],
            ['files', 'safe'],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->files = UploadedFile::getInstances($this, 'files');
            return true;
        }
        return false;
    }

    public function attributeLabels():array
    {
        return [
            'files'=>'Файлы'
        ];
    }
}