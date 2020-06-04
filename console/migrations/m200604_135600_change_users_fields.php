<?php

use yii\db\Migration;

/**
 * Class m200604_135600_change_users_fields
 */
class m200604_135600_change_users_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropColumn('{{%users}}', 'created_at');
        $this->dropColumn('{{%users}}', 'updated_at');
        $this->addColumn('{{%users}}', 'created_at', $this->integer());
        $this->addColumn('{{%users}}', 'updated_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'created_at');
        $this->dropColumn('{{%users}}', 'updated_at');
        $this->addColumn('{{%users}}', 'created_at',  $this->dateTime());
        $this->addColumn('{{%users}}', 'updated_at',  $this->dateTime());
    }


}
