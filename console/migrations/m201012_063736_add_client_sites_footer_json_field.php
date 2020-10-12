<?php

use yii\db\Migration;

/**
 * Class m201012_063736_add_client_sites_footer_json_field
 */
class m201012_063736_add_client_sites_footer_json_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'footer_json', 'JSON NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client_sites}}', 'footer_json');
    }
}
