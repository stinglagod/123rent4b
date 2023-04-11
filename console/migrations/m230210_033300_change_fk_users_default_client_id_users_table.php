<?php

use yii\db\Migration;

/**
 * Class m230210_033300_change_fk_users_default_client_id_users_table
 */
class m230210_033300_change_fk_users_default_client_id_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%fk-users-default_client_id}}', '{{%users}}');
        $this->addForeignKey('{{%fk-users-default_client_id}}', '{{%users}}', 'default_client_id', '{{%clients}}', 'id', 'SET NULL', 'RESTRICT');

        $this->dropForeignKey('{{%fk-users-default_site}}', '{{%users}}');
        $this->addForeignKey('{{%fk-users-default_site}}', '{{%users}}', 'default_site', '{{%shop_sites}}', 'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-users-default_client_id}}', '{{%users}}');
        $this->addForeignKey('{{%fk-users-default_client_id}}', '{{%users}}', 'default_client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        $this->dropForeignKey('{{%fk-users-default_site}}', '{{%users}}');
        $this->addForeignKey('{{%fk-users-default_site}}', '{{%users}}', 'default_site', '{{%shop_sites}}', 'id', 'CASCADE', 'RESTRICT');
    }
}
