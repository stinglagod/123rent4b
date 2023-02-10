<?php

use yii\db\Migration;

/**
 * Class m230209_070134_change_fk_client_sites_client_id_client_sites_table
 */
class m230209_070134_change_fk_client_sites_client_id_client_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%fk-client_sites-client_id}}','{{%client_sites}}');
        $this->addForeignKey('{{%fk-client_sites-client_id}}','{{%client_sites}}','client_id','{{%clients}}','id','CASCADE','CASCADE');

        $this->dropForeignKey('{{%fk-shop_categories-site_id}}', '{{%shop_categories}}');
        $this->addForeignKey('{{%fk-shop_categories-site_id}}', '{{%shop_categories}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE', 'CASCADE');

        $this->dropForeignKey('{{%fk-shop_products-category_id}}', '{{%shop_products}}');
        $this->addForeignKey('{{%fk-shop_products-category_id}}', '{{%shop_products}}', 'category_id', '{{%shop_categories}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-client_sites-client_id}}','{{%client_sites}}');
        $this->addForeignKey('{{%fk-client_sites-client_id}}','{{%client_sites}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');

        $this->dropForeignKey('{{%fk-shop_categories-site_id}}', '{{%shop_categories}}');
        $this->addForeignKey('{{%fk-shop_categories-site_id}}', '{{%shop_categories}}', 'site_id', '{{%client_sites}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->dropForeignKey('{{%fk-shop_products-category_id}}', '{{%shop_products}}');
        $this->addForeignKey('{{%fk-shop_products-category_id}}', '{{%shop_products}}', 'category_id', '{{%shop_categories}}', 'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230209_070134_change_fk_client_sites_client_id_client_sites_table cannot be reverted.\n";

        return false;
    }
    */
}
