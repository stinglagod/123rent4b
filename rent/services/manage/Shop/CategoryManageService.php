<?php

namespace rent\services\manage\Shop;

use paulzi\nestedsets\NestedSetsBehavior;
use rent\entities\Meta;
use rent\entities\Shop\Category;
use rent\forms\manage\Shop\CategoryForm;
use rent\repositories\Shop\CategoryRepository;
use rent\repositories\Shop\ProductRepository;
use yii\caching\TagDependency;

class CategoryManageService
{
    private $categories;
    private $products;

    public function __construct(CategoryRepository $categories, ProductRepository $products)
    {
        $this->categories = $categories;
        $this->products = $products;
    }

    public function create(CategoryForm $form): Category
    {
        $parent = $this->categories->get($form->parentId);
        $category = Category::create(
            $form->name,
            $form->slug,
            $form->code,
            $form->title,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
        $category->appendTo($parent);
        $this->categories->save($category);
        return $category;
    }

    public function edit($id, CategoryForm $form): void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        $category->edit(
            $form->name,
            $form->slug,
            $form->code,
            $form->title,
            $form->description,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords
            )
        );
//        var_dump($category->parent->id);exit;
        if ($form->parentId != $category->parent->id) {
            $parent = $this->categories->get($form->parentId);
            $category->appendTo($parent);
        }
        TagDependency::invalidate(\Yii::$app->cache, 'categories');
        $this->categories->save($category);
    }

    public function moveUp($id): void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if ($prev = $category->prev) {
            $category->insertBefore($prev);
        }
        $this->categories->save($category);
    }

    public function moveDown($id): void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if ($next = $category->next) {
            $category->insertAfter($next);
        }
        $this->categories->save($category);
    }

    public function remove($id): void
    {
        $category = $this->categories->get($id);
        $this->assertIsNotRoot($category);
        if ($this->products->existsByMainCategory($category->id)) {
            throw new \DomainException('Unable to remove category with products.');
        }
        $this->categories->remove($category);
    }

    private function assertIsNotRoot(Category $category): void
    {
        if ($category->isRoot()) {
            throw new \DomainException('Unable to manage the root category.');
        }
    }
    public function move($first_id,$second_id,$action): void
    {
        /**
         * @var $firstCategory NestedSetsBehavior
         * @var $secondCategory NestedSetsBehavior
         */
        $firstCategory = Category::findOne($first_id);
        $secondCategory = Category::findOne($second_id);

        $this->assertIsNotRoot($firstCategory);
        $this->assertIsNotRoot($secondCategory);

        switch ($action) {
            case 'after':
                $firstCategory->insertAfter($secondCategory);
                break;
            case 'before':
                $firstCategory->insertBefore($secondCategory);
                break;
            case 'over':
                $firstCategory->appendTo($secondCategory);
                break;
        }
        $this->categories->save($firstCategory);
    }
}