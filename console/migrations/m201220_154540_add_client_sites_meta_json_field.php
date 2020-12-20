<?php

use yii\db\Migration;

/**
 * Class m201220_154540_add_client_sites_meta_json_field
 */
class m201220_154540_add_client_sites_meta_json_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'meta_json', 'JSON NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client_sites}}', 'meta_json');
    }
}
