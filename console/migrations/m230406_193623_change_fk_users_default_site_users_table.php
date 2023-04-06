<?php

use yii\db\Migration;

/**
 * Class m230406_193623_change_fk_users_default_site_users_table
 */
class m230406_193623_change_fk_users_default_site_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%fk-users-default_site}}', '{{%users}}');
        $this->addForeignKey('{{%fk-users-default_site}}', '{{%users}}', 'default_site', '{{%client_sites}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-users-default_site}}', '{{%users}}');
        $this->addForeignKey('{{%fk-users-default_site}}', '{{%users}}', 'default_site', '{{%client_sites}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230406_193623_change_fk_users_default_site_users_table cannot be reverted.\n";

        return false;
    }
    */
}
