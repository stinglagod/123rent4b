<?php

use yii\db\Migration;

/**
 * Class m190329_223048_add_columns_to_action_table
 */
class m190329_223048_add_columns_to_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%action}}', 'sequence', $this->string(100));
        $this->addColumn('{{%action}}', 'order', $this->integer()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%action}}', 'sequence');
        $this->dropColumn('{{%action}}', 'order');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190329_223048_add_columns_to_action_table cannot be reverted.\n";

        return false;
    }
    */
}
