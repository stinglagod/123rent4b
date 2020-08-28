<?php

namespace rent\tests\unit\entities\Shop\Tag;

use Codeception\Test\Unit;
use rent\entities\Shop\Tag;

class CreateTest extends Unit
{
    public function testSuccess()
    {
        $tag = Tag::create(
            $name = 'Name',
            $slug = 'slug'
        );

        $this->assertEquals($name, $tag->name);
        $this->assertEquals($slug, $tag->slug);
    }
}
