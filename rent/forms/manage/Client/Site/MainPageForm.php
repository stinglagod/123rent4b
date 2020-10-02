<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Site\MainPage;
use rent\forms\CompositeForm;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * @property SliderForm[] $mainSliders
 */
class MainPageForm extends CompositeForm
{
    public $tmp;

    public function __construct(MainPage $mainPage=null,$config = [])
    {
        if ($mainPage) {
            if ($mainPage->mainSlider) {
                $key=0;
                $this->mainSliders= array_map(function ($item) use (&$key)  {
                    return new SliderForm($item['image'],$item['text'],$item['text2'],$item['url'],$item['urlText'],$key++);
                }, $mainPage->mainSlider);
            }

        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['tmp', 'safe'],
        ];
    }
    protected function internalForms(): array
    {
        return ['mainSliders'];
    }

}