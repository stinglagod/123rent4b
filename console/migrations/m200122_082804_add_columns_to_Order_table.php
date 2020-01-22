<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%Order}}`.
 */
class m200122_082804_add_columns_to_Order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'statusPaid_id', $this->integer()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order}}', 'statusPaid_id');
    }
}
