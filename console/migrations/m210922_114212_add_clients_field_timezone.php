<?php

use yii\db\Migration;

/**
 * Class m210922_114212_add_clients_field_timezone
 */
class m210922_114212_add_clients_field_timezone extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%clients}}', 'timezone', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%clients}}', 'timezone');
    }
}
