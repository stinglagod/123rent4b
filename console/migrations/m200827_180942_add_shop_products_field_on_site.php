<?php

use yii\db\Migration;

/**
 * Class m200827_180942_add_shop_products_field_on_site
 */
class m200827_180942_add_shop_products_field_on_site extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'on_site', $this->boolean());
        $this->createIndex('idx-shop_products-on_site', '{{%shop_products}}', 'on_site');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-shop_products-on_site', '{{%shop_products}}');
        $this->dropColumn('{{%shop_products}}', 'on_site');
    }


}
