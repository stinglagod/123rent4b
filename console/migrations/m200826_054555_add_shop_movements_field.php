<?php

use yii\db\Migration;

/**
 * Class m200826_054555_add_shop_movements_field
 */
class m200826_054555_add_shop_movements_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_movements}}', 'order_item_id', $this->integer()->unsigned());
        $this->createIndex('idx-shop_movements-order_item_id', '{{%shop_movements}}', 'order_item_id');
        $this->addForeignKey('fk-shop_movements-order_item_id', '{{%shop_movements}}', 'order_item_id', '{{%shop_order_items}}', 'id','SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-shop_movements-order_item_id', '{{%shop_movements}}');
        $this->dropIndex('idx-shop_movements-order_item_id', '{{%shop_movements}}');
        $this->dropColumn('{{%shop_movements}}', 'order_item_id');
    }
}
