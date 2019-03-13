<?php

use yii\db\Migration;

/**
 * Handles adding enum_item_to to table `{{%order_product}}`.
 */
class m190311_190537_add_enum_item_to_column_to_order_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//       Удалением(
        $this->dropColumn('{{%order_product}}','type');
        $this->addColumn('{{%order_product}}','type','ENUM("rent", "sale", "service","collect")');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order_product}}','type');
        $this->addColumn('{{%order_product}}','type','ENUM("rent", "sale", "service")');
    }
}
