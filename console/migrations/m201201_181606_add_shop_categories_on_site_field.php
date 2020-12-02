<?php

use yii\db\Migration;

/**
 * Class m201201_181606_add_shop_categories_on_site_field
 */
class m201201_181606_add_shop_categories_on_site_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_categories}}', 'on_site', $this->boolean());
        $this->createIndex('idx-shop_categories-on_site', '{{%shop_categories}}', 'on_site');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-shop_categories-on_site', '{{%shop_categories}}');
        $this->dropColumn('{{%shop_categories}}', 'on_site');
    }
}
