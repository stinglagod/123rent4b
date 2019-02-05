<?php

use yii\db\Migration;

/**
 * Handles the creation of table `attribute`.
 */
class m190204_122152_create_attribute_table extends Migration
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

//Таблица attribute
        $this->createTable('{{%attribute}}', [
            'id'            => $this->primaryKey()->unsigned(),
            'name'          => $this->string(255),
            'attr_name'     => $this->string(100),
        ], $tableOptions);
//Таблица product_attribute
        $this->createTable('{{%product_attribute}}', [
            'product_id'    => $this->integer()->unsigned()->notNull(),
            'attribute_id'  => $this->integer()->unsigned()->notNull(),
            'value'         => $this->string(255),
        ], $tableOptions);
        $this->addPrimaryKey(
            'pk-product_attribute',
            '{{%product_attribute}}',
            ['product_id','attribute_id']
        );
        $this->createIndex(
            'idx-attribute-attr_name',
            '{{%attribute}}',
            'attr_name'
        );
        $this->createIndex(
            'idx-product_attribute-product_id',
            '{{%product_attribute}}',
            'product_id'
        );
        $this->createIndex(
            'idx-product_attribute-attribute_id',
            '{{%product_attribute}}',
            'attribute_id'
        );
        $this->addForeignKey(
            'fk-product_attribute-product',
            '{{%product_attribute}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'RESTRICT');
        $this->addForeignKey(
            'fk-product_attribute-attribute',
            '{{%product_attribute}}',
            'attribute_id',
            '{{%attribute}}',
            'id',
            'CASCADE',
            'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product_attribute}}');
        $this->dropTable('{{%attribute}}');
    }
}
