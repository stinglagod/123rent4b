<?php

use yii\db\Migration;

/**
 * Class m210923_071023_add_users_default_client_id_field
 */
class m210923_071023_add_users_default_client_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'default_client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-users-default_client_id}}', '{{%users}}', 'default_client_id');
        $this->addForeignKey('{{%fk-users-default_client_id}}', '{{%users}}', 'default_client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-users-default_client_id}}', '{{%users}}');
        $this->dropIndex('{{%idx-users-default_client_id}}', '{{%users}}');
        $this->dropColumn('{{%users}}', 'default_client_id');
    }

}
