<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%User}}`.
 */
class m200121_023648_add_columns_to_User_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'avatar_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-user-avatar_id',
            '{{%user}}',
            'avatar_id',
            '{{%file}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user-avatar_id','{{%user}}');
        $this->dropColumn('{{%user}}', 'avatar_id');
    }
}
