<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m181027_074852_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

//======================================================================================================================
// Таблица категорий
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'parent_id' => $this->integer()->unsigned()->defaultValue(0),
            'name' =>   $this->string(255),
            'is_active' => 'ENUM("active", "inactive", "deleted")',
            'client_id' => $this->integer()->unsigned()->notNull(),
        ],$tableOptions);
        $sql = "ALTER TABLE {{%category}} ALTER is_active SET DEFAULT 'active'";
        $this->execute($sql);
        $this->addForeignKey(
            'fk-category-parent_id',
            '{{%category}}',
            'parent_id',
            '{{%category}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-category-client_id',
            '{{%category}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица соответсвий товаров и категорий
        $this->createTable('{{%product_category}}', [
            'product_id' => $this->integer()->unsigned()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull(),
        ],$tableOptions);
        $this->addPrimaryKey(
            'pk-product_category',
            '{{%product_category}}',
            ['product_id','category_id']
        );
        $this->createIndex(
            'idx-product_category-product_id',
            '{{%product_category}}',
            'product_id'
        );
        $this->createIndex(
            'idx-product_category-category_id',
            '{{%product_category}}',
            'category_id'
        );
        $this->addForeignKey(
            'fk-product_category-product_id',
            '{{%product_category}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-product_category-category_id',
            '{{%product_category}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product_category');
        $this->dropTable('category');

    }
}
