<?php

namespace frontend\widgets\Shop;

use rent\forms\manage\Client\Site\BannerForm;
use yii\base\Widget;

/**
 * @property BannerForm $content
 **/

class BannerWidget extends Widget
{
    public $content;

    public function __construct( $config = [])
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        if (empty($this->content['image']))
            return '';
        else
            return $this->render('banner',[
                'image'=>$this->content['image']->getThumbFileUrl('file','1171x300'),
                'name'=>$this->content['name'],
                'url'=>$this->content['url'],
            ]);
    }

}
