<?php

use yii\db\Migration;

/**
 * Class m200925_073520_add_client_sites_field_mainPage_json
 */
class m200925_073520_add_client_sites_field_mainPage_json extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'mainPage_json', 'JSON NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client_sites}}', 'mainPage_json');
    }


}
