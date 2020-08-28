<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%order}}`.
 */
class m200518_075442_add_columns_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'customer_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-order-customer_id',
            '{{%order}}',
            'customer_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addColumn('{{%order}}','telephone', $this->string(20));
        $this->addColumn('{{%order}}','comment', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-order-customer_id','{{%order}}');
        $this->dropColumn('{{%order}}', 'customer_id');
        $this->dropColumn('{{%order}}', 'telephone');
        $this->dropColumn('{{%order}}', 'comment');
    }
}
