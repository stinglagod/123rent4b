<?php

use yii\db\Migration;

/**
 * Class m190129_125517_add_rename_column_product_table
 */
class m190129_125517_add_rename_column_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%product}}','priceSelling','priceSale');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%product}}','priceSale','priceSelling');
    }


}
