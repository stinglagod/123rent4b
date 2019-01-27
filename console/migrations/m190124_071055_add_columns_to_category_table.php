<?php

use yii\db\Migration;

/**
 * Class m190124_071055_add_columns_to_category_table
 */
class m190124_071055_add_columns_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category}}', 'alias', $this->string(255));
        $this->createIndex(
            'idx-category-alias',
            '{{%category}}',
            'alias',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-category-alias','{{%category}}');
        $this->dropColumn('{{%category}}', 'alias');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190124_071055_add_columns_to_category_table cannot be reverted.\n";

        return false;
    }
    */
}
