<?php

use yii\db\Migration;

/**
 * Class m210923_060511_create_client_site_category_assignments
 */
class m210923_060511_create_client_site_assignments_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%client_site_assignments_category}}', [
            'category_id' => $this->integer()->notNull(),
            'site_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-client_site_assignments_category}}', '{{%client_site_assignments_category}}', ['category_id', 'site_id']);

        $this->createIndex('{{%idx-client_site_assignments_category-category_id}}', '{{%client_site_assignments_category}}', 'category_id');
        $this->createIndex('{{%idx-client_site_assignments_category-site_id}}', '{{%client_site_assignments_category}}', 'site_id');

        $this->addForeignKey('{{%fk-client_site_assignments_category-category_id}}', '{{%client_site_assignments_category}}', 'category_id', '{{%shop_categories}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-client_site_assignments_category-site_id}}', '{{%client_site_assignments_category}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client_site_assignments_category}}');
    }
}
