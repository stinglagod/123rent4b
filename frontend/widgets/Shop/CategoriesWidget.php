<?php

namespace frontend\widgets\Shop;

use rent\entities\Shop\Category;
use rent\readModels\Shop\CategoryReadRepository;
use yii\base\Widget;
use yii\helpers\Html;
use rent\readModels\Shop\views\CategoryView;

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
//        return Html::tag('div', implode(PHP_EOL,
//            array_map(
//                function (CategoryView $view) {
//                    $indent = ($view->category->depth > 1 ? str_repeat('&nbsp;&nbsp;&nbsp;', $view->category->depth - 1) . '- ' : '');
//                    $active = $this->active && ($this->active->id == $view->category->id || $this->active->isChildOf($view->category));
//                    return Html::a(
//                        $indent . Html::encode($view->category->name) . ' (' . $view->count . ')',
//                        ['/shop/catalog/category', 'id' => $view->category->id],
//                        ['class' => $active ? 'list-group-item active' : 'list-group-item']
//                    );
//                },
//                $this->categories->getTreeWithSubsOf($this->active))
//            ),
//            [
//            'class' => 'list-group',
//        ]);

        return $this->render('categories',[
            'name'=>$this->name,
            'categories'=>$this->categories->getTreeWithSubsOf2($this->active),
        ]);

    }

}