<?php

use yii\db\Migration;

/**
 * Class m200815_145105_add_order_payments_fields
 */
class m200815_145105_add_order_payments_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_payments}}', 'payer_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_payments}}', 'payer_name', $this->string());
        $this->addColumn('{{%shop_payments}}', 'payer_phone', $this->string());
        $this->addColumn('{{%shop_payments}}', 'payer_email', $this->string());
        $this->addColumn('{{%shop_payments}}', 'note', $this->string());
        $this->addColumn('{{%shop_payments}}', 'purpose_id', $this->integer());

        $this->createIndex('idx-shop_payments-payer_id', '{{%shop_payments}}', 'payer_id');

        $this->addForeignKey('fk-shop_payments-payer_id', '{{%shop_payments}}', 'payer_id', '{{%users}}', 'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-shop_payments-payer_id', '{{%shop_payments}}');

        $this->dropIndex('idx-shop_payments-payer_id', '{{%shop_payments}}');

        $this->dropColumn('{{%shop_payments}}', 'payer_id');
        $this->dropColumn('{{%shop_payments}}', 'payer_name');
        $this->dropColumn('{{%shop_payments}}', 'payer_phone');
        $this->dropColumn('{{%shop_payments}}', 'payer_email');
        $this->dropColumn('{{%shop_payments}}', 'note');
        $this->dropColumn('{{%shop_payments}}', 'purpose_id');
    }


}
