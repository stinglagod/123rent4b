<?php

use yii\db\Migration;

/**
 * Class m191118_024417_add_columns_to_orderProduct_table
 */
class m191118_024417_add_columns_to_orderProduct_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_product}}', 'comment', $this->string(256));
        $this->addColumn('{{%order_product}}', 'is_montage', $this->boolean()->defaultValue(false));
        $this->addColumn('{{%order_block}}', 'sort', $this->integer()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order_product}}', 'comment');
        $this->dropColumn('{{%order_product}}', 'is_montage');
        $this->dropColumn('{{%order_block}}', 'sort');
    }

}
