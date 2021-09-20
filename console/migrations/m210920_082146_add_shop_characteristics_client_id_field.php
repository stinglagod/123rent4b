<?php

use yii\db\Migration;

/**
 * Class m210920_082146_add_shop_characteristics_client_id_field
 */
class m210920_082146_add_shop_characteristics_client_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_characteristics}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_characteristics-client_id}}', '{{%shop_characteristics}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_characteristics-client_id}}', '{{%shop_characteristics}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-shop_characteristics-client_id}}', '{{%shop_characteristics}}');
        $this->dropIndex('{{%idx-shop_characteristics-client_id}}', '{{%shop_characteristics}}');
        $this->dropColumn('{{%shop_characteristics}}', 'client_id');
    }
}
