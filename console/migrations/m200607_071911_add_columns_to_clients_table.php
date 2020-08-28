<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%clients}}`.
 */
class m200607_071911_add_columns_to_clients_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //      добавляем столбцы в таблицу  {{%clients}}
        $this->addColumn('{{%clients}}', 'status', $this->smallInteger()->notNull()->defaultValue(10));
        $this->addColumn('{{%clients}}', 'created_at', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%clients}}', 'updated_at', $this->integer()->unsigned()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%clients}}', 'status');
        $this->dropColumn('{{%clients}}', 'created_at');
        $this->dropColumn('{{%clients}}', 'updated_at');
    }
}
