<?php

use yii\db\Migration;

/**
 * Class m200110_154631_add_columns_to_movement_table
 */
class m200110_154631_add_columns_to_movement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%movement}}', 'active', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%movement}}', 'active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200110_154631_add_columns_to_movement_table cannot be reverted.\n";

        return false;
    }
    */
}
