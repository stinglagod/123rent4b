<?php

use yii\db\Migration;

/**
 * Class m201224_214128_add_shop_product_priceCompensation_field
 */
class m201224_214128_add_shop_product_priceCompensation_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'priceCompensation', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_products}}', 'priceCompensation');
    }
}
