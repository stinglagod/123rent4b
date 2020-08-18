<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_order_items}}`.
 */
class m200818_093903_create_shop_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB ';

        $this->createTable('{{%shop_order_items}}', [
            'id' => $this->primaryKey()->unsigned(),
            'order_id' =>$this->integer()->unsigned(),
            'product_id' =>$this->integer()->unsigned(),
            'name'=> $this->string(),
            'qty'=> $this->integer(),
            'price' => $this->float(),
            'period_qty'=> $this->integer()->unsigned(),
            'period_id'=> $this->smallInteger(),
            'block_id' => $this->integer()->unsigned(),
            'block_name' => $this->string(100),
            'sort' => $this->smallInteger(),
            'type_id'=> $this->smallInteger(),
            'parent_id' => $this->integer()->unsigned(),
            'note'=>$this->string(),
            'is_montage' => $this->boolean(),
            'current_status' => $this->smallInteger(),
            'site_id'=>$this->integer()->unsigned(),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer(),
            'author_id'=>$this->integer()->unsigned(),
            'lastChangeUser_id'=>$this->integer()->unsigned(),
        ], $tableOptions." COMMENT 'Таблица позиций заказа'");

        $this->createIndex('idx-shop_order_items-order_id','{{%shop_order_items}}','order_id');
        $this->createIndex('idx-shop_order_items-product_id','{{%shop_order_items}}','product_id');
        $this->createIndex('idx-shop_order_items-block_id','{{%shop_order_items}}','block_id');
        $this->createIndex('idx-shop_order_items-type_id','{{%shop_order_items}}','type_id');
        $this->createIndex('idx-shop_order_items-current_status','{{%shop_order_items}}','current_status');
        $this->createIndex('idx-shop_order_items-site_id','{{%shop_order_items}}','site_id');

        $this->addForeignKey('fk-shop_order_items-order_id', '{{%shop_order_items}}', 'order_id', '{{%shop_orders}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_order_items-product_id', '{{%shop_order_items}}', 'product_id', '{{%shop_products}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_order_items-parent_id', '{{%shop_order_items}}', 'parent_id', '{{%shop_order_items}}', 'id', 'CASCADE');

        $this->addForeignKey('fk-shop_order_items-site_id', '{{%shop_order_items}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_order_items-author_id', '{{%shop_order_items}}', 'author_id', '{{%users}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_order_items-lastChangeUser_id', '{{%shop_order_items}}', 'lastChangeUser_id', '{{%users}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_order_items}}');
    }
}
