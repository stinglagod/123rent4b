<?php

use yii\db\Migration;

/**
 * Class m200923_103314_add_client_sites_field_logo
 */
class m200923_103314_add_client_sites_field_logo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'logo_id', $this->integer()->unsigned());

        $this->createIndex('{{%idx-client_sites-logo_id}}', '{{%client_sites}}', 'logo_id');

        $this->addForeignKey('{{%fk-client_sites-logo_id}}', '{{%client_sites}}', 'logo_id', '{{%client_files}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-client_sites-logo_id}}', '{{%client_sites}}');

        $this->dropColumn('{{%client_sites}}', 'logo_id');
    }
}
