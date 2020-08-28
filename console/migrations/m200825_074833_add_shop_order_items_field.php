<?php

use yii\db\Migration;

/**
 * Class m200825_074833_add_shop_order_items_field
 */
class m200825_074833_add_shop_order_items_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_order_items}}', 'service_id', $this->integer()->unsigned());
        $this->createIndex('idx-shop_order_items-service_id', '{{%shop_order_items}}', 'service_id');
        $this->addForeignKey('fk-shop_order_items-service_id', '{{%shop_order_items}}', 'service_id', '{{%shop_services}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-shop_order_items-service_id', '{{%shop_order_items}}');
        $this->dropIndex('idx-shop_order_items-service_id', '{{%shop_order_items}}');
        $this->dropColumn('{{%shop_order_items}}', 'service_id');
    }

}
