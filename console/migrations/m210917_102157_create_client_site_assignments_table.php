<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client_site_assignments}}`.
 */
class m210917_102157_create_client_site_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%client_site_assignments}}', [
            'product_id' => $this->integer()->unsigned()->notNull(),
            'site_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-client_site_assignments}}', '{{%client_site_assignments}}', ['product_id', 'site_id']);

        $this->createIndex('{{%idx-client_site_assignments-product_id}}', '{{%client_site_assignments}}', 'product_id');
        $this->createIndex('{{%idx-client_site_assignments-site_id}}', '{{%client_site_assignments}}', 'site_id');

        $this->addForeignKey('{{%fk-client_site_assignments-product_id}}', '{{%client_site_assignments}}', 'product_id', '{{%shop_products}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-client_site_assignments-site_id}}', '{{%client_site_assignments}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client_site_assignments}}');
    }
}
