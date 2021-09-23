<?php

use yii\db\Migration;

/**
 * Class m210923_061033_add_shop_categories_show_without_goods_field
 */
class m210923_061033_add_shop_categories_show_without_goods_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_categories}}', 'show_without_goods', $this->boolean()->defaultValue(false));
        $this->createIndex('{{%idx-shop_categories-show_without_goods}}', '{{%shop_categories}}', 'show_without_goods');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-shop_categories-show_without_goods}}', '{{%shop_categories}}');
        $this->dropColumn('{{%shop_categories}}', 'show_without_goods');
    }

}
