<?php

use yii\db\Migration;

/**
 * Class m220824_052056_add_payments_sum_with_sign
 */
class m220824_052056_add_payments_sum_with_sign extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_payments}}', 'sumWithSign', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_payments}}', 'sumWithSign');
    }
}
