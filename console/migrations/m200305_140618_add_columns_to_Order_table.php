<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%Order}}`.
 */
class m200305_140618_add_columns_to_Order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'googleEvent_id', $this->string(1024));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order}}', 'googleEvent_id');
    }
}
