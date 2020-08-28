<?php

use yii\db\Migration;

/**
 * Class m200827_190317_add_users_field_defult_site
 */
class m200827_190317_add_users_field_defult_site extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'default_site', $this->integer()->unsigned());
        $this->createIndex('idx-users-default_site','{{%users}}','default_site');
        $this->addForeignKey('fk-users-default_site', '{{%users}}', 'default_site', '{{%client_sites}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-users-default_site', '{{%users}}');
        $this->dropIndex('idx-users-default_site','{{%users}}');
        $this->dropColumn('{{%users}}', 'default_site');
    }

}
