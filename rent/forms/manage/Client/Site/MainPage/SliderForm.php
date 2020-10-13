<?php

namespace rent\forms\manage\Client\Site\MainPage;

use rent\entities\Client\File;
use yii\base\Model;
use yii\web\UploadedFile;

class SliderForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;
    public $text;
    public $text2;
    public $url;
    public $urlText;
    public $key;

    public function __construct( $image=null,$text=null,$text2=null,$url=null,$urlText=null,$key=null, $config = [])
    {
        $this->image=$image?:null;
        $this->text=$text?:null;
        $this->text2=$text2?:null;
        $this->url=$url?:null;
        $this->urlText=$urlText?:null;
        $this->key=$key?:null;
    }
    public function rules(): array
    {
        return [
//            ['image', 'image'],
//            ['image', 'each', 'rule' => ['image']],
            [['text','text2','url','urlText'], 'string'],
        ];
    }

    public function beforeValidate(): bool
    {

        if (parent::beforeValidate()) {
//            var_dump($this->key);
//            var_dump(UploadedFile::getInstance($this, '[0]image'));
//            var_dump(UploadedFile::getInstance($this, '[1]image'));
//            exit;
//            var_dump($this);

            if ($file=UploadedFile::getInstance($this, '['.(int)$this->key. ']image')) {
//                var_dump($this->key);
//                var_dump($this);
//                var_dump($file);
//                exit;
                $this->image = File::create($file);
            }

            return true;
        }
        return false;
    }
}