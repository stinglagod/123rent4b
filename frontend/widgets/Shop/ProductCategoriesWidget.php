<?php

namespace frontend\widgets\Shop;

use rent\entities\Shop\Category\Category;
use rent\readModels\Shop\CategoryReadRepository;
use yii\base\Widget;

class ProductCategoriesWidget extends Widget
{
    public $content;
    /** @var \rent\entities\Shop\Category\Category|null */

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        if (($this->content)and($this->content['category'])) {
            return $this->render('product-categories',[
                'category'=> $this->content['category']
            ]);
        } else {
            return '';
        }


    }

}