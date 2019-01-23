<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `columns_to_product`.
 */
class m190122_114306_drop_columns_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        //      Удаляем старые столбцы
        $tableSchema=\Yii::$app->db->getTableSchema('{{%product}}', true);
        if (isset($tableSchema->columns['primeСost']))
            $this->dropColumn('{{%product}}','primeСost');
        if (isset($tableSchema->columns['cost']))
            $this->dropColumn('{{%product}}','cost');
        if (isset($tableSchema->columns['priceType_id'])) {
            $this->dropForeignKey('fk-product-priceType_id','{{%product}}');
            $this->dropColumn('{{%product}}','priceType_id');
        }
//        Удаляем таблицу
        if (\Yii::$app->db->getTableSchema('{{%priceType}}', true) !== null) {
            $this->dropTable('{{%priceType}}');
        }




    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
