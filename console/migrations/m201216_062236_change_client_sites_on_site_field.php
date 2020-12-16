<?php

use yii\db\Migration;

/**
 * Class m201216_062236_change_client_sites_on_site_field
 */
class m201216_062236_change_client_sites_on_site_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%shop_products}}', 'on_site', $this->boolean()->defaultValue(0));
        $this->alterColumn('{{%shop_categories}}', 'on_site', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%shop_products}}', 'on_site', $this->boolean());
        $this->alterColumn('{{%shop_categories}}', 'on_site', $this->boolean());
    }
}
