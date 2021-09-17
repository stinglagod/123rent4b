<?php

use yii\db\Migration;

/**
 * Class m210916_103652_add_srop_cart_items_site_id_field
 */
class m210916_103652_add_srop_cart_items_site_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_cart_items}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->createIndex('{{%idx-shop_cart_items-site_id}}', '{{%shop_cart_items}}', 'site_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-shop_cart_items-site_id}}', '{{%shop_cart_items}}');
        $this->dropColumn('{{%shop_cart_items}}', 'site_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210916_103652_add_srop_cart_items_site_id_field cannot be reverted.\n";

        return false;
    }
    */
}
