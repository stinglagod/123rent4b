<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\File;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * @property File $image
 **/

class BannerForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;
    public $name;
    public $url;
    public $key;

    public function __construct( $image=null,$name=null,$url=null,$key=null, $config = [])
    {
        $this->image=$image?:null;
        $this->name=$name?:null;
        $this->url=$url?:null;
        $this->key=$key?:null;

    }
    public function rules(): array
    {
        return [
//            ['image', 'image'],
//            ['image', 'each', 'rule' => ['image']],
            [['name','url'], 'string'],
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            if ($file=UploadedFile::getInstance($this, '['.(int)$this->key. ']image')) {
                $this->image = File::create($file);
            }
            return true;
        }
        return false;
    }
}