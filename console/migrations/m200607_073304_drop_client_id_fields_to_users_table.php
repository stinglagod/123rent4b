<?php

use yii\db\Migration;

/**
 */
class m200607_073304_drop_client_id_fields_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-users-client_id','{{%users}}');
        $this->dropColumn('{{%users}}','client_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%users}}', 'client_id', $this->integer()->unsigned());
        $this->addForeignKey('fk-users-client_id','{{%users}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
    }
}
