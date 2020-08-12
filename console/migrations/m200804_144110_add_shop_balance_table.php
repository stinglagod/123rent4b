<?php

use yii\db\Migration;

/**
 * Class m200804_144110_add_shop_balance_table
 */
class m200804_144110_add_shop_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB ';

//        $this->createTable('{{%shop_actions}}', [
//            'id' => $this->primaryKey()->unsigned(),
//            'name' => $this->string(100),
//            'sing' => $this->boolean(true),             //true = '+', false = '-'
//            'shortName' => $this->string(100),
//            'sequence' =>$this->string(100),
//            'order'=>$this->integer(),
//            'antipod_id'=>$this->integer()->unsigned(),
//            'actionType_id'=>$this->smallInteger()->unsigned()->notNull()
//        ],$tableOptions."COMMENT 'Таблица действий'");


        $this->createTable('{{%shop_movements}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
            'date_begin' => $this->integer()->notNull(),
            'date_end' => $this->integer(),
            'qty' => $this->integer()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'type_id' => $this->smallInteger()->unsigned()->notNull(),
            'depend_id' => $this->integer()->unsigned()->null(),
            'site_id' => $this->integer()->unsigned()->notNull(),
            'active' => $this->boolean()->notNull(),
            'readOnly' => $this->boolean()->defaultValue(false),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer(),
            'autor_id'=>$this->integer()->unsigned(),
            'lastChangeUser_id'=>$this->integer()->unsigned()
        ],$tableOptions."COMMENT 'Таблица движений товара'");

        $this->createIndex('idx-shop_movements-date_begin','{{%shop_movements}}','date_begin');
        $this->createIndex('idx-shop_movements-date_end','{{%shop_movements}}','date_end');
        $this->createIndex('idx-shop_movements-site_id','{{%shop_movements}}','site_id');
        $this->createIndex('idx-shop_movements-product_id','{{%shop_movements}}','product_id');

        $this->addForeignKey(
            'fk-shop_movements-product_id',
            '{{%shop_movements}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-shop_movements-autor_id',
            '{{%shop_movements}}',
            'autor_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-shop_movements-lastChangeUser_id',
            '{{%shop_movements}}',
            'lastChangeUser_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-shop_movements-site_id',
            '{{%shop_movements}}',
            'site_id',
            '{{%client_sites}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-shop_movements-depend_id',
            '{{%shop_movements}}',
            'depend_id',
            '{{%shop_movements}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createTable('{{%shop_balance}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dateTime' => $this->integer(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'qty' => $this->integer(),
            'site_id'=>$this->integer()->unsigned()->notNull(),
            'movement_id'=>$this->integer()->unsigned()->notNull(),
            'typeMovement_id'=>$this->smallInteger()->unsigned()->notNull(),
        ], $tableOptions."COMMENT 'Таблица Остатков. Для быстроты подсчета остатка'");

        $this->createIndex('idx-shop_balance-dateTime','{{%shop_balance}}','dateTime');
        $this->createIndex('idx-shop_balance-site_id','{{%shop_balance}}','site_id');
        $this->createIndex('idx-shop_balance-typeMovement_id','{{%shop_balance}}','typeMovement_id');

        $this->addForeignKey('fk-shop_balance-product_id','{{%shop_balance}}','product_id','{{%shop_products}}','id','CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-shop_balance-movement_id','{{%shop_balance}}','movement_id','{{%shop_movements}}','id','CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_balance}}');
        $this->dropTable('{{%shop_movements}}');
    }


}
