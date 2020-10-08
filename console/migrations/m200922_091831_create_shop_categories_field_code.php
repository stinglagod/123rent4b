<?php

use yii\db\Migration;

/**
 * Class m200922_091831_create_shop_categories_field_code
 */
class m200922_091831_create_shop_categories_field_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_categories}}', 'code', $this->string());
        $this->createIndex('{{%idx-shop_categories-code}}', '{{%shop_categories}}', ['code','site_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-shop_categories-code}}', '{{%shop_categories}}');
        $this->dropColumn('{{%shop_categories}}', 'code');
    }

}
