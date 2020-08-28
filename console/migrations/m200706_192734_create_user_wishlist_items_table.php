<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_wishlist_items}}`.
 */
class m200706_192734_create_user_wishlist_items_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%user_wishlist_items}}', [
            'user_id' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'site_id'=>$this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-user_wishlist_items}}', '{{%user_wishlist_items}}', ['user_id', 'product_id','site_id']);

        $this->createIndex('{{%idx-user_wishlist_items-user_id}}', '{{%user_wishlist_items}}', 'user_id');
        $this->createIndex('{{%idx-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id');
        $this->createIndex('{{%idx-user_wishlist_items-site_id}}', '{{%user_wishlist_items}}', 'site_id');

        $this->addForeignKey('{{%fk-user_wishlist_items-user_id}}', '{{%user_wishlist_items}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-user_wishlist_items-site_id}}', '{{%user_wishlist_items}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_wishlist_items}}');
    }
}
