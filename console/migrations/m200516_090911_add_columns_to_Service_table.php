<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%Service}}`.
 */
class m200516_090911_add_columns_to_Service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%service}}', 'serviceType_id', $this->integer()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%service}}', 'serviceType_id');
    }
}
