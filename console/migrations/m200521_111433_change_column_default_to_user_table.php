<?php

use yii\db\Migration;

/**
 * Class m200521_111433_change_column_default_to_user_table
 */
class m200521_111433_change_column_default_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user','telephone', $this->string(20));
        $this->alterColumn('user','surname', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200521_111433_change_column_default_to_user_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200521_111433_change_column_default_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
