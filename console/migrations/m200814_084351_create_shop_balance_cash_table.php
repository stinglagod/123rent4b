<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_balance_cash}}`.
 */
class m200814_084351_create_shop_balance_cash_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB ';

        $this->createTable('{{%shop_balance_cash}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dateTime' => $this->integer(),
            'order_id' =>$this->integer()->unsigned(),
            'sum' => $this->float(),
            'site_id'=>$this->integer()->unsigned()->notNull(),
            'payment_id'=>$this->integer()->unsigned()->notNull(),
        ], $tableOptions." COMMENT 'Таблица Остатков денег. Для быстроты подсчета денежных движений'");

        $this->createIndex('idx-shop_balance_cash-dateTime','{{%shop_balance_cash}}','dateTime');
        $this->createIndex('idx-shop_balance_cash-site_id','{{%shop_balance_cash}}','site_id');
        $this->createIndex('idx-shop_balance_cash-payment_id','{{%shop_balance_cash}}','payment_id');

        $this->addForeignKey('fk-shop_balance_cash-order_id', '{{%shop_balance_cash}}', 'order_id', '{{%shop_orders}}', 'id', 'SET NULL');
        $this->addForeignKey('fk-shop_balance_cash-site_id', '{{%shop_balance_cash}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-shop_balance_cash-payment_id', '{{%shop_balance_cash}}', 'payment_id', '{{%shop_payments}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_balance_cash}}');
    }
}
