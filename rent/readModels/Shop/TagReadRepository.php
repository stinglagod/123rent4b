<?php

namespace rent\readModels\Shop;

use rent\entities\Shop\Tag;

class TagReadRepository
{
    public function find($id): ?Tag
    {
        return Tag::findOne($id);
    }
}