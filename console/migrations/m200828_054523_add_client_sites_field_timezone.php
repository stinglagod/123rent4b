<?php

use yii\db\Migration;

/**
 * Class m200828_054523_add_users_field_timezone
 */
class m200828_054523_add_client_sites_field_timezone extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%client_sites}}', 'timezone', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client_sites}}', 'timezone');
    }
}
