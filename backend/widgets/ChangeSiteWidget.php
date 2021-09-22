<?php


namespace backend\widgets;


use yii\base\Widget;

class ChangeSiteWidget extends Widget
{
    public $content;

    public function __construct( $config = [])
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        return $this->render('changeSite',[]);
    }

}