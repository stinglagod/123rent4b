<?php

use yii\db\Migration;

/**
 * Class m200804_143907_change_id_fields_to_shop_products
 */
class m200804_143907_change_id_fields_to_shop_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-shop_category_assignments-product_id','{{%shop_category_assignments}}');
        $this->alterColumn('{{%shop_category_assignments}}', 'product_id', $this->integer()->unsigned());

        $this->dropForeignKey('fk-shop_modifications-product_id','{{%shop_modifications}}');
        $this->alterColumn('{{%shop_modifications}}','product_id', $this->integer()->unsigned());

        $this->dropForeignKey('fk-shop_photos-product_id','{{%shop_photos}}');
        $this->alterColumn('{{%shop_photos}}','product_id', $this->integer()->unsigned());

        $this->dropForeignKey('fk-shop_related_assignments-product_id','{{%shop_related_assignments}}');
        $this->alterColumn('{{%shop_related_assignments}}','product_id', $this->integer()->unsigned());

        $this->dropForeignKey('fk-shop_related_assignments-related_id','{{%shop_related_assignments}}');
        $this->alterColumn('{{%shop_related_assignments}}','related_id', $this->integer()->unsigned());

        $this->dropForeignKey('fk-shop_reviews-product_id','{{%shop_reviews}}');
        $this->alterColumn('{{%shop_reviews}}','product_id', $this->integer()->unsigned());

        $this->dropForeignKey('fk-shop_tag_assignments-product_id','{{%shop_tag_assignments}}');
        $this->alterColumn('{{%shop_tag_assignments}}','product_id', $this->integer()->unsigned());

        $this->dropForeignKey('fk-shop_values-product_id','{{%shop_values}}');
        $this->alterColumn('{{%shop_values}}','product_id', $this->integer()->unsigned());

        $this->dropForeignKey('fk-user_wishlist_items-product_id','{{%user_wishlist_items}}');
        $this->alterColumn('{{%user_wishlist_items}}','product_id', $this->integer()->unsigned());

        $this->alterColumn('{{%shop_products}}', 'id', $this->integer()->unsigned());

        $this->addForeignKey('{{%fk-shop_category_assignments-product_id}}', '{{%shop_category_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_modifications-product_id}}', '{{%shop_modifications}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_photos-product_id}}', '{{%shop_photos}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_related_assignments-product_id}}', '{{%shop_related_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_related_assignments-related_id}}', '{{%shop_related_assignments}}', 'related_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_reviews-product_id}}', '{{%shop_reviews}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_tag_assignments-product_id}}', '{{%shop_tag_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_values-product_id}}', '{{%shop_values}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-shop_category_assignments-product_id','{{%shop_category_assignments}}');
        $this->alterColumn('{{%shop_category_assignments}}', 'product_id', $this->integer());

        $this->dropForeignKey('fk-shop_modifications-product_id','{{%shop_modifications}}');
        $this->alterColumn('{{%shop_modifications}}','product_id', $this->integer());

        $this->dropForeignKey('fk-shop_photos-product_id','{{%shop_photos}}');
        $this->alterColumn('{{%shop_photos}}','product_id', $this->integer());

        $this->dropForeignKey('fk-shop_related_assignments-product_id','{{%shop_related_assignments}}');
        $this->alterColumn('{{%shop_related_assignments}}','product_id', $this->integer());

        $this->dropForeignKey('fk-shop_related_assignments-related_id','{{%shop_related_assignments}}');
        $this->alterColumn('{{%shop_related_assignments}}','related_id', $this->integer());

        $this->dropForeignKey('fk-shop_reviews-product_id','{{%shop_reviews}}');
        $this->alterColumn('{{%shop_reviews}}','product_id', $this->integer());

        $this->dropForeignKey('fk-shop_tag_assignments-product_id','{{%shop_tag_assignments}}');
        $this->alterColumn('{{%shop_tag_assignments}}','product_id', $this->integer());

        $this->dropForeignKey('fk-shop_values-product_id','{{%shop_values}}');
        $this->alterColumn('{{%shop_values}}','product_id', $this->integer());

        $this->dropForeignKey('fk-user_wishlist_items-product_id','{{%user_wishlist_items}}');
        $this->alterColumn('{{%user_wishlist_items}}','product_id', $this->integer());

        $this->alterColumn('{{%shop_products}}', 'id', $this->integer());

        $this->addForeignKey('{{%fk-shop_category_assignments-product_id}}', '{{%shop_category_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_modifications-product_id}}', '{{%shop_modifications}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_photos-product_id}}', '{{%shop_photos}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_related_assignments-product_id}}', '{{%shop_related_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_related_assignments-related_id}}', '{{%shop_related_assignments}}', 'related_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_reviews-product_id}}', '{{%shop_reviews}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_tag_assignments-product_id}}', '{{%shop_tag_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-shop_values-product_id}}', '{{%shop_values}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-user_wishlist_items-product_id}}', '{{%user_wishlist_items}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
    }

}
