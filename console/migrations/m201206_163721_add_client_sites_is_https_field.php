<?php

use yii\db\Migration;

/**
 * Class m201206_163721_add_client_sites_is_https_field
 */
class m201206_163721_add_client_sites_is_https_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'is_https', $this->boolean()->defaultValue(false));
        $this->createIndex('{{idx-client_sites-on_site}}', '{{%client_sites}}', 'is_https');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-client_sites-on_site', '{{%client_sites}}');
        $this->dropColumn('{{%client_sites}}', 'is_https');
    }

}
