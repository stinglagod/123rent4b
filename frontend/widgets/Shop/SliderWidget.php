<?php

namespace frontend\widgets\Shop;

use yii\base\Widget;

class SliderWidget extends Widget
{
    public $sliders;
    public $images;
    public $firstTexts;
    public $secondTexts;
    public $urls;
    public $urlTexts;



    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        return $this->render('slider',[
            'sliders'=>$this->sliders,
            'images'=>$this->images,
            'firstTexts'=>$this->firstTexts,
            'secondTexts'=>$this->secondTexts,
            'urls'=>$this->urls,
            'urlTexts'=>$this->urlTexts,
        ]);

    }

}