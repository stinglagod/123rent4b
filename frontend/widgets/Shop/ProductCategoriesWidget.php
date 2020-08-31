<?php

namespace frontend\widgets\Shop;

use rent\entities\Shop\Category;
use rent\readModels\Shop\CategoryReadRepository;
use yii\base\Widget;

class ProductCategoriesWidget extends Widget
{
    /** @var Category|null */

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        return $this->render('product-categories',[
        ]);

    }

}