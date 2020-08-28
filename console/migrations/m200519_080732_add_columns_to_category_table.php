<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%category}}`.
 */
class m200519_080732_add_columns_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category}}', 'on_site', $this->boolean(false));
        $this->addColumn('{{%product}}', 'on_site', $this->boolean(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%category}}', 'on_site');
        $this->dropColumn('{{%product}}', 'on_site');
    }
}
