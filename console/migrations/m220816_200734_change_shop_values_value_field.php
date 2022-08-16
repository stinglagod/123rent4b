<?php

use yii\db\Migration;

/**
 * Class m220816_200734_change_shop_values_value_field
 */
class m220816_200734_change_shop_values_value_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%shop_values}}', 'value', $this->string(1024));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%shop_values}}', 'value', $this->string(512));
    }
}
