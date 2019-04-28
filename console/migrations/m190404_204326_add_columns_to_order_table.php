<?php

use yii\db\Migration;

/**
 * Class m190404_204326_add_columns_to_order_table
 */
class m190404_204326_add_columns_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'status_id', $this->integer()->unsigned());
        $this->addColumn('{{%order}}', 'responsible_id', $this->integer()->unsigned());

        $this->addForeignKey(
            'fk-order-status_id',
            '{{%order}}',
            'status_id',
            '{{%action}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-order-responsible_id',
            '{{%order}}',
            'responsible_id',
            '{{%user}}',
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
        $this->dropForeignKey('fk-order-status_id','{{%order}}');
        $this->dropForeignKey('fk-order-responsible_id','{{%order}}');

        $this->dropColumn('{{%order}}', 'status_id');
        $this->dropColumn('{{%order}}', 'responsible_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190404_204326_add_columns_to_order_table cannot be reverted.\n";

        return false;
    }
    */
}
