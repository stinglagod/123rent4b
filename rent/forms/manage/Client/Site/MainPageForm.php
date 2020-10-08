<?php

namespace rent\forms\manage\Client\Site;

use rent\entities\Client\Site\MainPage;
use rent\forms\CompositeForm;
use rent\forms\manage\Client\Site\MainPage\BannerForm;
use rent\forms\manage\Client\Site\MainPage\SliderForm;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * @property SliderForm[] $mainSliders
 * @property BannerForm[] $banners
 * @property BannerForm $banner1
 * @property BannerForm $banner2
 * @property BannerForm $banner3
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
        return ['mainSliders','banners','banner1','banner2','banner3'];
    }

}