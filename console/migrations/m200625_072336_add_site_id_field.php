<?php

use yii\db\Migration;

/**
 * Class m200625_072336_add_site_id_field
 */
class m200625_072336_add_site_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-shop_tags-client_id','{{%shop_tags}}');
        $this->dropForeignKey('fk-shop_brands-client_id','{{%shop_brands}}');
        $this->dropForeignKey('fk-shop_characteristics-client_id','{{%shop_characteristics}}');
        $this->dropForeignKey('fk-shop_products-client_id','{{%shop_products}}');

        $this->dropColumn('{{%shop_tags}}', 'client_id');
        $this->dropColumn('{{%shop_brands}}', 'client_id');
        $this->dropColumn('{{%shop_characteristics}}', 'client_id');
        $this->dropColumn('{{%shop_products}}', 'client_id');


//
        $this->addColumn('{{%shop_tags}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_brands}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_characteristics}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_products}}', 'site_id', $this->integer()->unsigned()->notNull());

        $this->createIndex('{{%idx-shop_tags-site_id}}', '{{%shop_tags}}', 'site_id');
        $this->createIndex('{{%idx-shop_brands-site_id}}', '{{%shop_brands}}', 'site_id');
        $this->createIndex('{{%idx-shop_characteristics-site_id}}', '{{%shop_characteristics}}', 'site_id');
        $this->createIndex('{{%idx-shop_products-site_id}}', '{{%shop_products}}', 'site_id');

        $this->addForeignKey('fk-shop_tags-site_id','{{%shop_tags}}','site_id','{{%client_sites}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_brands-site_id','{{%shop_brands}}','site_id','{{%client_sites}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_characteristics-site_id','{{%shop_characteristics}}','site_id','{{%client_sites}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_products-site_id','{{%shop_products}}','site_id','{{%client_sites}}','id','RESTRICT','RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%shop_tags}}', 'client_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_brands}}', 'client_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_characteristics}}', 'client_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_products}}', 'client_id', $this->integer()->unsigned()->notNull());

        $this->addForeignKey('fk-shop_tags-client_id','{{%shop_tags}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_brands-client_id','{{%shop_brands}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_characteristics-client_id','{{%shop_characteristics}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_products-client_id','{{%shop_products}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');


//
        $this->dropForeignKey('{{%fk-shop_tags-site_id}}', '{{%shop_tags}}');
        $this->dropForeignKey('{{%fk-shop_brands-site_id}}', '{{%shop_brands}}');
        $this->dropForeignKey('{{%fk-shop_characteristics-site_id}}', '{{%shop_characteristics}}');
        $this->dropForeignKey('{{%fk-shop_products-site_id}}', '{{%shop_products}}');

        $this->dropIndex('{{%idx-shop_tags-site_id}}', '{{%shop_tags}}');
        $this->dropIndex('{{%idx-shop_brands-site_id}}', '{{%shop_brands}}');
        $this->dropIndex('{{%idx-shop_characteristics-site_id}}', '{{%shop_characteristics}}');
        $this->dropIndex('{{%idx-shop_products-site_id}}', '{{%shop_products}}');

        $this->dropColumn('{{%shop_tags}}', 'site_id');
        $this->dropColumn('{{%shop_brands}}', 'site_id');
        $this->dropColumn('{{%shop_characteristics}}', 'site_id');
        $this->dropColumn('{{%shop_products}}', 'site_id');
    }
}
