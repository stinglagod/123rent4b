<?php

use yii\db\Migration;

/**
 * Class m190122_111908_add_columns_to_product_table
 */
class m190122_111908_add_columns_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', 'priceRent', $this->double()->unsigned());
        $this->addColumn('{{%product}}', 'priceSelling', $this->double()->unsigned());
        $this->addColumn('{{%product}}', 'pricePrime', $this->double()->unsigned());
        $this->addColumn('{{%product}}', 'productType', 'ENUM("product", "service")');
//      Значение по умолчанию
        $sql = "ALTER TABLE {{%product}} ALTER productType SET DEFAULT 'product'";
        $this->execute($sql);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', 'priceRent');
        $this->dropColumn('{{%product}}', 'priceSelling');
        $this->dropColumn('{{%product}}', 'pricePrime');
        $this->dropColumn('{{%product}}', 'priceType');


    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190122_111908_add_columns_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
