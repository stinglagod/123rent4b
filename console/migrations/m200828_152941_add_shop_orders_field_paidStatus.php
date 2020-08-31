<?php

use yii\db\Migration;

/**
 * Class m200828_152941_add_shop_orders_field_paidStatus
 */
class m200828_152941_add_shop_orders_field_paidStatus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_orders}}', 'paidStatus', $this->smallInteger()->unsigned());
        $this->createIndex('idx-shop_orders-paidStatus','{{%shop_orders}}','paidStatus');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-shop_orders-paidStatus','{{%shop_orders}}');
        $this->dropColumn('{{%shop_orders}}', 'paidStatus');
    }
}
