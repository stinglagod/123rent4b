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

        $this->createTable('{{%shop_actions}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
            'sing' => $this->boolean(true),             //true = '+', false = '-'
            'shortName' => $this->string(100),
            'sequence' =>$this->string(100),
            'order'=>$this->integer(),
            'antipod_id'=>$this->integer()->unsigned(),
            'actionType_id'=>$this->smallInteger()->unsigned()->notNull()
        ],$tableOptions."COMMENT 'Таблица действий'");


        $this->createTable('{{%shop_movements}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
            'dateTime' => $this->integer()->notNull(),
            'qty' => $this->integer()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'action_id' => $this->integer()->unsigned(),
            'site_id' => $this->integer()->unsigned()->notNull(),
            'created_at'=>$this->integer(),
            'updated_at'=>$this->integer(),
            'autor_id'=>$this->integer()->unsigned(),
            'lastChangeUser_id'=>$this->integer()->unsigned()
        ],$tableOptions."COMMENT 'Таблица движений товара'");

        $this->createIndex('idx-shop_movements-dateTime','{{%shop_movements}}','dateTime');
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
            'fk-shop_movements-action_id',
            '{{%shop_movements}}',
            'action_id',
            '{{%action}}',
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

        $this->createTable('{{%shop_balance}}', [
            'id' => $this->integer()->unsigned()->notNull(),
            'dateTime' => $this->integer(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'qty' => $this->integer(),
            'site_id'=>$this->integer()->unsigned()->notNull(),
            'movement_id'=>$this->integer()->unsigned()->notNull(),
            'actionType_id'=>$this->smallInteger()->unsigned()->notNull(),
        ], $tableOptions."COMMENT 'Таблица Остатков. Для быстроты подсчета остатка'");

        $this->createIndex('idx-shop_balance-dateTime','{{%shop_balance}}','dateTime');
        $this->createIndex('idx-shop_balance-site_id','{{%shop_balance}}','site_id');
        $this->createIndex('idx-shop_balance-actionType_id','{{%shop_balance}}','actionType_id');

        $this->addForeignKey('fk-shop_balance-product_id','{{%shop_balance}}','product_id','{{%shop_products}}','id');
        $this->addForeignKey('fk-shop_balance-movement_id','{{%shop_balance}}','movement_id','{{%shop_movements}}','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_balance}}');
        $this->dropTable('{{%shop_movements}}');
        $this->dropTable('{{%shop_actions}}');
    }


}
