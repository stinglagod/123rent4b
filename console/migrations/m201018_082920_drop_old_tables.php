<?php

use yii\db\Migration;

/**
 * Class m201018_082920_drop_old_tables
 */
class m201018_082920_drop_old_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('{{%order_product_action}}');
        $this->delete('{{%order_product}}');
        $this->delete('{{%order_block}}');
        $this->delete('{{%product_attribute}}');
        $this->delete('{{%product_category}}');
        $this->delete('{{%periodType}}');
        $this->delete('{{%block}}');
        $this->delete('{{%cashType}}');
        $this->delete('{{%cash}}');
        $this->delete('{{%category}}');
        $this->delete('{{%movement}}');
        $this->delete('{{%service}}');
        $this->delete('{{%tag}}');
        $this->delete('{{%product}}');
        $this->delete('{{%status}}');
//        $this->delete('{{%file}}');

        $this->dropTable('{{%order_product_action}}');
        $this->dropTable('{{%order_product}}');
        $this->dropTable('{{%order_block}}');
        $this->dropTable('{{%product_attribute}}');
        $this->dropTable('{{%product_category}}');

        $this->dropTable('{{%periodType}}');
        $this->dropTable('{{%block}}');

        $this->dropTable('{{%category}}');
        $this->dropTable('{{%service}}');
        $this->dropTable('{{%tag}}');
        $this->dropTable('{{%order_cash}}');
        $this->dropTable('{{%order}}');
        $this->dropTable('{{%cash}}');
        $this->dropTable('{{%cashType}}');

        $this->dropTable('{{%status}}');
        $this->dropTable('{{%ostatok}}');
        $this->dropTable('{{%movement}}');
        $this->dropTable('{{%product}}');

        $this->dropTable('{{%action}}');
        $this->dropTable('{{%actionType}}');
        $this->dropTable('{{%attribute}}');

//        $this->dropTable('{{%file}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201018_082920_drop_old_tables cannot be reverted.\n";

        return true;
    }
}
