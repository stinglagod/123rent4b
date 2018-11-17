<?php

use yii\db\Migration;

/**
 * Class m181027_121345_add_column_to_user_table
 */
class m181027_121345_add_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'client_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-user-client_id',
            '{{%user}}',
            'client_id',
            '{{%client}}',
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
        $this->dropForeignKey('fk-user-client_id','{{%user}}');
        $this->dropColumn('{{%user}}', 'client_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181027_121345_add_column_to_user_table cannot be reverted.\n";

        return false;
    }
    */
}
