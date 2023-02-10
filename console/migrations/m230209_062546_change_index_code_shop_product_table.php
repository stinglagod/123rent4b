<?php

use yii\db\Migration;

/**
 * Class m230209_062546_change_index_code_shop_product_table
 */
class m230209_062546_change_index_code_shop_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('{{%idx-shop_products-code}}', '{{%shop_products}}');
        $this->createIndex('{{%idx-shop_products-code}}', '{{%shop_products}}', ['code','client_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-shop_products-code}}', '{{%shop_products}}');
        $this->createIndex('{{%idx-shop_products-code}}', '{{%shop_products}}', 'code', true);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230209_062546_change_index_code_shop_product_table cannot be reverted.\n";

        return false;
    }
    */
}
