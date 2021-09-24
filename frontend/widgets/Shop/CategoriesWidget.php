<?php

namespace frontend\widgets\Shop;

use rent\entities\Shop\Category\Category;
use rent\readModels\Shop\CategoryReadRepository;
use yii\base\Widget;

class CategoriesWidget extends Widget
{
    /** @var \rent\entities\Shop\Category\Category|null */
    public $active;
    public $name;

    private $categories;

    public function __construct(CategoryReadRepository $categories, $config = [])
    {
        parent::__construct($config);
        $this->categories = $categories;
    }

    public function run(): string
    {
//        return \Yii::$app->params['site_id'];
        return $this->render('categories',[
            'name'=>$this->name,
            'categories'=>$this->categories->getTreeWithSubsOf2($this->active),
        ]);

    }

}