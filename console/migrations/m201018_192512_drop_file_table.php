<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%file}}`.
 */
class m201018_192512_drop_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try{
            $this->dropForeignKey('{{%fk-user-avatar_id}}','{{%users}}');

            $this->dropColumn('{{%users}}','avatar_id');

            $this->dropTable('{{%file}}');
        }  catch (\Exception $e) {
            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201018_082920_drop_old_tables cannot be reverted.\n";

        return true;
    }
}
