<?php

namespace rent\tests\unit\entities\Shop\Category;

use Codeception\Test\Unit;
use rent\entities\Shop\Category\Category;
use rent\entities\Meta;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $category = Category::create(
            $name = 'Name',
            $slug = 'slug',
            $code = 'code',
            $title = 'Full header',
            $description = 'Description',
            $meta = new Meta('Title', 'Description', 'Keywords'),
            $showWithoutGood=true,
            $onSite=false
        );

        $this->assertEquals($name, $category->name);
        $this->assertEquals($slug, $category->slug);
        $this->assertEquals($code, $category->code);
        $this->assertEquals($title, $category->title);
        $this->assertEquals($description, $category->description);
        $this->assertEquals($meta, $category->meta);
        $this->assertEquals($showWithoutGood, $category->show_without_goods);
        $this->assertEquals($onSite, $category->on_site);
    }
}