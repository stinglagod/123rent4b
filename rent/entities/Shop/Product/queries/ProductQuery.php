<?php

namespace rent\entities\Shop\Product\queries;

use rent\entities\Shop\Product\Product;
use rent\helpers\AppHelper;
use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null)
    {
        $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Product::STATUS_ACTIVE,
        ]);
        if (AppHelper::isSite()) {
            $this->andWhere([
                ($alias ? $alias . '.' : '') . 'on_site' => 1,
            ]);
        }
        return $this;
    }
}