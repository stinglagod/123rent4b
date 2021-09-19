<?php

use yii\db\Migration;

/**
 * Class m210919_094911_add_shop_tags_client_id_field
 */
class m210919_094911_add_shop_tags_client_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%shop_tags}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_tags-client_id}}', '{{%shop_tags}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_tags-client_id}}', '{{%shop_tags}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-shop_tags-client_id}}', '{{%shop_tags}}');
        $this->dropIndex('{{%idx-shop_tags-client_id}}', '{{%shop_tags}}');
        $this->dropColumn('{{%shop_tags}}', 'client_id');
    }
}
