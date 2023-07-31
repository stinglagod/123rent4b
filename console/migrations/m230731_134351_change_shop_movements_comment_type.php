<?php

use yii\db\Migration;

/**
 * Class m230731_134351_change_shop_movements_comment_type
 */
class m230731_134351_change_shop_movements_comment_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('shop_movements', 'comment', $this->string(255)->defaultValue(null)->comment('Комментарий'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('shop_movements', 'comment', $this->text()->comment('Комментарий'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230731_134351_change_shop_movements_comment_type cannot be reverted.\n";

        return false;
    }
    */
}
