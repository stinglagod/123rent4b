<?php

use yii\db\Migration;

/**
 * Class m190325_020753_add_columns_to_action_table
 */
class m190325_020753_add_columns_to_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%action}}', 'shortName', $this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%action}}','shortName');
    }

}
