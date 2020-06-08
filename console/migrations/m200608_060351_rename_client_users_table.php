<?php

use yii\db\Migration;

/**
 * Class m200608_060351_rename_client_users_table
 */
class m200608_060351_rename_client_users_table extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('fk-client_user-client_id','{{%client_users}}');
        $this->dropForeignKey('fk-client_user-user_id','{{%client_users}}');
        //      переименовываем таблицу client_users
        $this->renameTable('{{%client_users}}', '{{%client_user_assignments}}');
        //
        $this->addForeignKey('fk-client_user_assignments-client_id','{{%client_user_assignments}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-client_user_assignments-user_id','{{%client_user_assignments}}','user_id','{{%users}}','id','RESTRICT','RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-client_user_assignments-client_id','{{%client_user_assignments}}');
        $this->dropForeignKey('fk-client_user_assignments-user_id','{{%client_user_assignments}}');

        $this->renameTable('{{%client_user_assignments}}', '{{%client_users}}');

        $this->addForeignKey('fk-client_user-client_id','{{%client_users}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-client_user-user_id','{{%client_users}}','user_id','{{%users}}','id','RESTRICT','RESTRICT');
    }
}
