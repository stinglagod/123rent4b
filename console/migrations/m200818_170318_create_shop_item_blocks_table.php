<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_item_blocks}}`.
 */
class m200818_170318_create_shop_item_blocks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB ';

        $this->createTable('{{%shop_item_blocks}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name'=>$this->string(),
            'sort'=>$this->integer(),
            'site_id'=>$this->integer()->unsigned(),
        ], $tableOptions." COMMENT 'Таблица позиций заказа'");

        $this->createIndex('idx-shop_item_blocks-site_id','{{%shop_item_blocks}}','site_id');
        $this->addForeignKey('fk-shop_item_blocks-site_id', '{{%shop_item_blocks}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_item_blocks}}');
    }
}
