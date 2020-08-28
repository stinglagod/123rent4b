<?php

use yii\db\Migration;

/**
 * Class m200610_092826_add_column_to_shop_categories
 */
class m200610_092826_add_column_to_shop_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('{{%client_sites}}',['id'=>1]);
        $this->delete('{{%shop_categories}}',['id'=>1]);

        $this->addColumn('{{%shop_categories}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->createIndex('{{%idx-shop_categories-site_id}}', '{{%shop_categories}}', 'site_id');
        $this->addForeignKey('{{%fk-shop_categories-site_id}}', '{{%shop_categories}}', 'site_id', '{{%client_sites}}', 'id', 'RESTRICT', 'RESTRICT');

        $this->dropForeignKey('fk-shop_categories-client_id','{{%shop_categories}}');
        $this->dropColumn('{{%shop_categories}}','client_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-shop_categories-site_id}}', '{{%shop_categories}}');
        $this->dropIndex('{{%idx-shop_categories-site_id}}', '{{%shop_categories}}');
        $this->dropColumn('{{%shop_categories}}', 'site_id');

        $this->addColumn('{{%shop_categories}}', 'client_id', $this->integer()->unsigned()->notNull());
        $this->addForeignKey('fk-shop_categories-client_id','{{%shop_categories}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT'
        );

    }


}
