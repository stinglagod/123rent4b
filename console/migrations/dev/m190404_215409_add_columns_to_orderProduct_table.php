<?php

use yii\db\Migration;

/**
 * Class m190404_215409_add_columns_to_orderProduct_table
 */
class m190404_215409_add_columns_to_orderProduct_table extends Migration
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
            '{{%action}}',
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

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190404_215409_add_columns_to_orderProduct_table cannot be reverted.\n";

        return false;
    }
    */
}
