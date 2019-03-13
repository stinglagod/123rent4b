<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_product_block}}`.
 */
class m190220_091020_create_order_product_block_table extends Migration
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

        $this->createTable('{{%order_block}}', [
            'id'            => $this->primaryKey()->unsigned(),
            'name'          => $this->string(255),
            'note'          => $this->string(255),
            'order_id'      => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-order_block-order_id',
            '{{%order_block}}',
            'order_id',
            '{{%order}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createTable('{{%block}}', [
            'id'            => $this->primaryKey()->unsigned(),
            'name'          => $this->string(255),
            'client_id'     => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-block-client_id',
            '{{%block}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

//      добавляем поля в таблицу order_product
        $this->addColumn('{{%order_product}}', 'orderBlock_id', $this->integer()->unsigned());
        $this->addColumn('{{%order_product}}', 'sort', $this->smallInteger()->unsigned());

        $this->addForeignKey(
            'fk-order_product-orderBlock_id',
            '{{%order_product}}',
            'orderBlock_id',
            '{{%order_block}}',
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
        $this->dropForeignKey('fk-order_product-orderBlock_id','{{%order_product}}');
        $this->dropColumn('{{%order_product}}', 'orderBlock_id');
        $this->dropColumn('{{%order_product}}', 'sort');

        $this->dropTable('{{%order_block}}');
        $this->dropTable('{{%block}}');
    }
}
