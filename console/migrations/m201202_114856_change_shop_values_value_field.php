<?php

use yii\db\Migration;

/**
 * Class m201202_114856_change_shop_values_value_field
 */
class m201202_114856_change_shop_values_value_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%shop_values}}', 'value', $this->string(512));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%shop_values}}', 'value', $this->string());
    }
}
