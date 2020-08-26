<?php

use yii\db\Migration;

/**
 * Class m200826_065034_change_shop_movements_field_product_id
 */
class m200826_065034_change_shop_movements_field_product_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-shop_movements-product_id','{{%shop_movements}}');
        $this->alterColumn('{{%shop_movements}}', 'product_id', $this->integer()->unsigned());
        $this->addForeignKey('{{%fk-shop_movements-product_id}}', '{{%shop_movements}}', 'product_id', '{{%shop_products}}', 'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-shop_movements-product_id','{{%shop_movements}}');
        $this->alterColumn('{{%shop_movements}}', 'product_id', $this->integer()->unsigned()->notNull());
        $this->addForeignKey('fk-shop_movements-product_id', '{{%shop_movements}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
    }
}
