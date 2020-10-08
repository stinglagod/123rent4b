<?php

use yii\db\Migration;

/**
 * Class m200907_090840_change_id_fields_to_shops_products
 */
class m200907_090840_change_id_fields_to_shops_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-shop_category_assignments-product_id','{{%shop_category_assignments}}');

        $this->dropForeignKey('fk-shop_modifications-product_id','{{%shop_modifications}}');

        $this->dropForeignKey('fk-shop_photos-product_id','{{%shop_photos}}');

        $this->dropForeignKey('fk-shop_related_assignments-product_id','{{%shop_related_assignments}}');

        $this->dropForeignKey('fk-shop_related_assignments-related_id','{{%shop_related_assignments}}');

        $this->dropForeignKey('fk-shop_reviews-product_id','{{%shop_reviews}}');

        $this->dropForeignKey('fk-shop_tag_assignments-product_id','{{%shop_tag_assignments}}');

        $this->dropForeignKey('fk-shop_values-product_id','{{%shop_values}}');

        $this->dropForeignKey('fk-user_wishlist_items-product_id','{{%user_wishlist_items}}');

        $this->dropForeignKey('fk-shop_movements-product_id','{{%shop_movements}}');

        $this->dropForeignKey('fk-shop_balance-product_id','{{%shop_balance}}');

        $this->dropForeignKey('fk-shop_cart_items-product_id','{{%shop_cart_items}}');

        $this->dropForeignKey('fk-shop_order_items-product_id','{{%shop_order_items}}');

        $this->dropPrimaryKey('id','{{%shop_products}}');

        $this->alterColumn('{{%shop_products}}', 'id', $this->primaryKey()->unsigned());

        $this->addForeignKey('{{%fk-shop_category_assignments-product_id}}', '{{%shop_category_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_modifications-product_id}}', '{{%shop_modifications}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_photos-product_id}}', '{{%shop_photos}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_related_assignments-product_id}}', '{{%shop_related_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_related_assignments-related_id}}', '{{%shop_related_assignments}}', 'related_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_reviews-product_id}}', '{{%shop_reviews}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_tag_assignments-product_id}}', '{{%shop_tag_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_values-product_id}}', '{{%shop_values}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_movements-product_id}}', '{{%shop_movements}}', 'product_id', '{{%shop_products}}', 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk-shop_balance-product_id','{{%shop_balance}}','product_id','{{%shop_products}}','id','CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_cart_items-product_id}}', '{{%shop_cart_items}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_order_items-product_id', '{{%shop_order_items}}', 'product_id', '{{%shop_products}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
