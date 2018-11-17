<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m181029_081344_create_category_table extends Migration
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
        $this->createTable('{{%category}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),

            'client_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

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
            'category_id' => $this->bigInteger()->unsigned()->notNull(),
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
