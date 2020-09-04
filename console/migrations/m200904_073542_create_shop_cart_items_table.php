<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_cart_items}}`.
 */
class m200904_073542_create_shop_cart_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%shop_cart_items}}', [
            'id' => $this->bigPrimaryKey(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'type_id' => $this->integer(),
            'quantity' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-shop_cart_items-user_id}}', '{{%shop_cart_items}}', 'user_id');
        $this->createIndex('{{%idx-shop_cart_items-product_id}}', '{{%shop_cart_items}}', 'product_id');

        $this->addForeignKey('{{%fk-shop_cart_items-user_id}}', '{{%shop_cart_items}}', 'user_id', '{{%users}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-shop_cart_items-product_id}}', '{{%shop_cart_items}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_cart_items}}');
    }
}
