<?php

use yii\db\Migration;

/**
 * Class m200625_124226_add_columns_to_shop_products
 */
class m200625_124226_add_columns_to_shop_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'priceSale_new', $this->integer()->unsigned());
        $this->addColumn('{{%shop_products}}', 'priceSale_old', $this->integer()->unsigned());
        $this->addColumn('{{%shop_products}}', 'priceRent_new', $this->integer()->unsigned());
        $this->addColumn('{{%shop_products}}', 'priceRent_old', $this->integer()->unsigned());
        $this->addColumn('{{%shop_products}}', 'priceCost', $this->integer()->unsigned());

        $this->dropColumn('{{%shop_products}}', 'price_old');
        $this->dropColumn('{{%shop_products}}', 'price_new');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_products}}', 'priceSale_new');
        $this->dropColumn('{{%shop_products}}', 'priceSale_old');
        $this->dropColumn('{{%shop_products}}', 'priceRent_new');
        $this->dropColumn('{{%shop_products}}', 'priceRent_old');
        $this->dropColumn('{{%shop_products}}', 'priceCost');

        $this->addColumn('{{%shop_products}}', 'price_old', $this->integer());
        $this->addColumn('{{%shop_products}}', 'price_new', $this->integer());
    }

}
