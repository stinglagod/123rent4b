<?php

use yii\db\Migration;

/**
 * Class m210918_132446_add_shop_products_client_id_field
 */
class m210918_132446_add_shop_products_client_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_products}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_products-client_id}}', '{{%shop_products}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_products-client_id}}', '{{%shop_products}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-shop_products-client_id}}', '{{%shop_products}}');
        $this->dropIndex('{{%idx-shop_products-client_id}}', '{{%shop_products}}');
        $this->dropColumn('{{%shop_products}}', 'client_id');
    }
}
