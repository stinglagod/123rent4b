<?php

use yii\db\Migration;

/**
 * Class m201109_094913_add_client_sites_reCaptcha_json_field
 */
class m201109_094913_add_client_sites_reCaptcha_json_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'reCaptcha_json', 'JSON NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client_sites}}', 'reCaptcha_json');
    }
}
