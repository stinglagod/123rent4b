<?php

use yii\db\Migration;

/**
 * Class m230422_111716_add_status_field_to_shop_services_table
 */
class m230422_111716_add_status_field_to_shop_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_services}}', 'status', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%shop_services}}', 'status');
    }

}
