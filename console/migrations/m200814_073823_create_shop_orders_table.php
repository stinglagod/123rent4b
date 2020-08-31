<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_orders}}`.
 */
class m200814_073823_create_shop_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB ';
        $this->createTable('{{%shop_orders}}', [
            'id' => $this->primaryKey()->unsigned(),
            'code' => $this->string(50),
            'date_begin' => $this->integer()->notNull(),
            'date_end' => $this->integer(),
            'name' => $this->string(100),
            'customer_id' => $this->integer()->unsigned(),
            'customer_name' =>$this->string(),
            'customer_phone' =>$this->string(50),
            'customer_email' =>$this->string(100),
            'delivery_address'=> $this->text(),
            'responsible_id'=>$this->integer()->unsigned(),
            'responsible_name'=>$this->string(),
            'responsibleHistory_json' => 'JSON NOT NULL',
            'statuses_json' => 'JSON NOT NULL',
            'current_status' => $this->integer()->notNull(),
            'cancel_reason' => $this->text(),
            'note' => $this->text(),
            'cost'=>$this->double()->unsigned(),
            'site_id' => $this->integer()->unsigned()->notNull(),
            'googleEvent_id'=> $this->string(1024),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer(),
            'author_id'=>$this->integer()->unsigned(),
            'lastChangeUser_id'=>$this->integer()->unsigned(),


        ],$tableOptions." COMMENT 'Таблица заказов'");

        $this->createIndex('idx-shop_orders-date_begin','{{%shop_orders}}','date_begin');
        $this->createIndex('idx-shop_orders-date_end','{{%shop_orders}}','date_end');
        $this->createIndex('idx-shop_orders-customer_id','{{%shop_orders}}','customer_id');
        $this->createIndex('idx-shop_orders-current_status','{{%shop_orders}}','current_status');
        $this->createIndex('idx-shop_orders-cost','{{%shop_orders}}','cost');
        $this->createIndex('idx-shop_orders-site_id','{{%shop_orders}}','site_id');
        $this->createIndex('idx-shop_orders-responsible_id','{{%shop_orders}}','responsible_id');

        $this->addForeignKey('fk-shop_orders-customer_id', '{{%shop_orders}}', 'customer_id', '{{%users}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_orders-responsible_id','{{%shop_orders}}', 'responsible_id','{{%users}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_orders-site_id', '{{%shop_orders}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_orders-author_id','{{%shop_orders}}', 'author_id','{{%users}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_orders-lastChangeUser_id','{{%shop_orders}}','lastChangeUser_id','{{%users}}','id','SET NULL');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_orders}}');
    }
}
