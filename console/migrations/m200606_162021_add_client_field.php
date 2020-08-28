<?php

use yii\db\Migration;

/**
 * Class m200606_162021_add_client_field
 */
class m200606_162021_add_client_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('{{%shop_categories}}', ['id' => 1]);
        $this->addColumn('{{%shop_tags}}', 'client_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_brands}}', 'client_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_categories}}', 'client_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_characteristics}}', 'client_id', $this->integer()->unsigned()->notNull());
        $this->addColumn('{{%shop_products}}', 'client_id', $this->integer()->unsigned()->notNull());

        $this->insert('{{%shop_categories}}', [
            'id' => 1,
            'name' => '',
            'slug' => 'root',
            'title' => null,
            'description' => null,
            'meta_json' => '{}',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
            'client_id'=>1
        ]);

        $this->addForeignKey(
            'fk-shop_tags-client_id',
            '{{%shop_tags}}',
            'client_id',
            '{{%client}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-shop_brands-client_id',
            '{{%shop_brands}}',
            'client_id',
            '{{%client}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-shop_categories-client_id',
            '{{%shop_categories}}',
            'client_id',
            '{{%client}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-shop_characteristics-client_id',
            '{{%shop_characteristics}}',
            'client_id',
            '{{%client}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-shop_products-client_id',
            '{{%shop_products}}',
            'client_id',
            '{{%client}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-shop_tags-client_id','{{%shop_tags}}');
        $this->dropForeignKey('fk-shop_brands-client_id','{{%shop_brands}}');
        $this->dropForeignKey('fk-shop_categories-client_id','{{%shop_categories}}');
        $this->dropForeignKey('fk-shop_characteristics-client_id','{{%shop_characteristics}}');
        $this->dropForeignKey('fk-shop_products-client_id','{{%shop_products}}');

        $this->dropColumn('{{%shop_tags}}', 'client_id');
        $this->dropColumn('{{%shop_brands}}', 'client_id');
        $this->dropColumn('{{%shop_categories}}', 'client_id');
        $this->dropColumn('{{%shop_characteristics}}', 'client_id');
        $this->dropColumn('{{%shop_products}}', 'client_id');
    }
}
