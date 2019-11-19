<?php

use yii\db\Migration;

/**
 * Class m191118_072834_add_columns_to_orderProduct_table
 */
class m191118_072834_add_columns_to_orderProduct_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_product}}', 'service_id', $this->integer()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order_product}}', 'service_id');
    }

}
