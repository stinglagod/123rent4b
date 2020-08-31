<?php

use yii\db\Migration;

/**
 * Class m200608_100159_add_column_to_client_sites
 */
class m200608_100159_add_column_to_client_sites extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'domain', $this->string()->notNull()->unique());
        $this->createIndex('{{%idx-client_sites-domain}}', '{{%client_sites}}', 'domain');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-client_sites-domain}}', '{{%client_sites}}');
        $this->dropColumn('{{%client_sites}}', 'domain');
    }


}
