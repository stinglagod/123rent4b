<?php

namespace rent\readModels\Shop;

use rent\entities\Shop\Brand;

class BrandReadRepository
{
    public function find($id): ?Brand
    {
        return Brand::findOne($id);
    }
}