<?php

use yii\db\Migration;

/**
 * Class m200112_091942_add_columns_to_orderProduct_table
 */
class m200112_091942_add_columns_to_orderProduct_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_product}}', 'status_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-order_product-status_id',
            '{{%order_product}}',
            'status_id',
            '{{%status}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-order_product-status_id','{{%order_product}}');
        $this->dropColumn('{{%order_product}}', 'status_id');
    }

}
