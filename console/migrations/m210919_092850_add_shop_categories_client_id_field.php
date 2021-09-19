<?php

use yii\db\Migration;

/**
 * Class m210919_092850_add_shop_categories_client_id_field
 */
class m210919_092850_add_shop_categories_client_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_categories}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_categories-client_id}}', '{{%shop_categories}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_categories-client_id}}', '{{%shop_categories}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-shop_categories-client_id}}', '{{%shop_categories}}');
        $this->dropIndex('{{%idx-shop_categories-client_id}}', '{{%shop_categories}}');
        $this->dropColumn('{{%shop_categories}}', 'client_id');
    }


}
