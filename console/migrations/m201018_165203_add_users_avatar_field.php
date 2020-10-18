<?php

use yii\db\Migration;

/**
 * Class m201018_165203_add_users_avatar_field
 */
class m201018_165203_add_users_avatar_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'avatar', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'avatar');
    }
}
