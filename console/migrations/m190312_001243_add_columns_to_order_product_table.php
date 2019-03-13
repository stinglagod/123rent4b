<?php

use yii\db\Migration;

/**
 * Class m190312_001243_add_columns_to_order_product_table
 */
class m190312_001243_add_columns_to_order_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_product}}', 'parent_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-order_product-parent_id',
            '{{%order_product}}',
            'parent_id',
            '{{%order_product}}',
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
        $this->dropForeignKey('fk-order_product-parent_id','{{%order_product}}');
        $this->dropColumn('{{%order_product}}', 'parent_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190312_001243_add_columns_to_order_product_table cannot be reverted.\n";

        return false;
    }
    */
}
