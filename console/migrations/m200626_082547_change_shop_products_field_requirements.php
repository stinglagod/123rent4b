<?php

use yii\db\Migration;

/**
 * Class m200626_082547_change_shop_products_field_requirements
 */
class m200626_082547_change_shop_products_field_requirements extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%shop_products}}', 'brand_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%shop_products}}', 'brand_id', $this->integer()->notNull());
    }

}
