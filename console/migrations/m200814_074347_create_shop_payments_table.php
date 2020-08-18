<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_payments}}`.
 */
class m200814_074347_create_shop_payments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB ';
        $this->createTable('{{%shop_payments}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dateTime' => $this->integer(),
            'order_id' =>$this->integer()->unsigned(),
            'type_id'=>$this->integer()->unsigned(),
            'sum'=>$this->double(),
            'responsible_id'=>$this->integer()->unsigned(),
            'responsible_name'=>$this->string(),
            'site_id'=>$this->integer()->unsigned(),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer(),
            'author_id'=>$this->integer()->unsigned(),
            'lastChangeUser_id'=>$this->integer()->unsigned(),
            'active' => $this->boolean()->notNull(),

        ],$tableOptions." COMMENT 'Таблица движения денег'");

        $this->createIndex('idx-shop_payments-dateTime','{{%shop_payments}}','dateTime');
        $this->createIndex('idx-shop_payments-order_id','{{%shop_payments}}','order_id');
        $this->createIndex('idx-shop_payments-type_id','{{%shop_payments}}','type_id');
        $this->createIndex('idx-shop_payments-responsible_id','{{%shop_payments}}','responsible_id');
        $this->createIndex('idx-shop_payments-site_id','{{%shop_payments}}','site_id');
        $this->createIndex('idx-shop_payments-active','{{%shop_payments}}','active');

        $this->addForeignKey('fk-shop_payments-order_id', '{{%shop_payments}}', 'order_id', '{{%shop_orders}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_payments-responsible_id', '{{%shop_payments}}', 'responsible_id', '{{%users}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_payments-site_id', '{{%shop_payments}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_payments-author_id', '{{%shop_payments}}', 'author_id', '{{%users}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_payments-lastChangeUser_id', '{{%shop_payments}}', 'lastChangeUser_id', '{{%users}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_payments}}');
    }
}
