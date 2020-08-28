<?php

use yii\db\Migration;

/**
 * Class m200604_171310_change_users_field_requirements
 */
class m200604_171310_change_users_field_requirements extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%users}}', 'name', $this->string(255));
        $this->alterColumn('{{%users}}', 'surname', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%users}}', 'name', $this->string(255)->notNull());
        $this->alterColumn('{{%users}}', 'surname', $this->string(255)->notNull());
    }
}
