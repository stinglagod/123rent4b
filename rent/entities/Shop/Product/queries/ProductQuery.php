<?php

namespace rent\entities\Shop\Product\queries;

use rent\entities\Shop\Product\Product;
use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Product::STATUS_ACTIVE,
        ]);
    }
    public function onSite($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'on_site' => 1,
        ]);
    }
}