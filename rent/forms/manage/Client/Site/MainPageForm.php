<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Site\MainPage;
use rent\forms\CompositeForm;
use rent\forms\manage\Client\Site\MainPage\BannerForm;
use rent\forms\manage\Client\Site\MainPage\MainPageCategoryForm;
use rent\forms\manage\Client\Site\MainPage\SliderForm;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * @property SliderForm[] $mainSliders
 * @property BannerForm[] $banners
 * @property MainPageCategoryForm[] categories
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
            if ($mainPage->banners) {
                $key=0;
                $this->banners=array_map(function ($item) use (&$key)  {
                    return new BannerForm($item['image'],$item['name'],$item['url'],$key++);
                }, $mainPage->banners);
            }
            if ($mainPage->categories) {
                $key=0;

                $this->categories=array_map(function ($item) use (&$key)  {
                    return new MainPageCategoryForm($item['category'],$key++);
                }, $mainPage->categories);
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
        return ['mainSliders','banners','categories'];
    }

}