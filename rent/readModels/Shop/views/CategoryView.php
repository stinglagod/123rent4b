<?php

namespace rent\readModels\Shop\views;

use rent\entities\Shop\Category\Category;

class CategoryView
{
    public $category;
    public $count;

    public function __construct(Category $category, $count)
    {
        $this->category = $category;
        $this->count = $count;
    }
}