<?php

use yii\db\Migration;

/**
 * Class m230724_093126_add_shop_movements_comment
 */
class m230724_093126_add_shop_movements_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_movements}}', 'comment', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_movements}}', 'comment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230724_093126_add_shop_movements_comment cannot be reverted.\n";

        return false;
    }
    */
}
