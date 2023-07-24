<?php

use yii\db\Migration;

/**
 * Class m230724_100018_add_shop_balance_comment
 */
class m230724_100018_add_shop_balance_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('shop_balance', 'comment', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('shop_balance', 'comment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230724_100018_add_shop_balance_comment cannot be reverted.\n";

        return false;
    }
    */
}
