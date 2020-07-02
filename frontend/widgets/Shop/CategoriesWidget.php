<?php

namespace frontend\widgets\Shop;

use rent\entities\Shop\Category;
use rent\readModels\Shop\CategoryReadRepository;
use yii\base\Widget;

class CategoriesWidget extends Widget
{
    /** @var Category|null */
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
        return $this->render('categories',[
            'name'=>$this->name,
            'categories'=>$this->categories->getTreeWithSubsOf2($this->active),
        ]);

    }

}