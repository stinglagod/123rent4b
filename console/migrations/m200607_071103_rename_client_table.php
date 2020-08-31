<?php

use yii\db\Migration;

/**
 * Class m200607_071103_rename_client_table
 */
class m200607_071103_rename_client_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //      удаляем связи
        $this->dropForeignKey('fk-block-client_id','{{%block}}');
        $this->dropForeignKey('fk-cash-client_id','{{%cash}}');
        $this->dropForeignKey('fk-category-client_id','{{%category}}');
        $this->dropForeignKey('fk-client_user-client_id','{{%client_users}}');
        $this->dropForeignKey('fk-file-client_id','{{%file}}');
        $this->dropForeignKey('fk-movement-client_id','{{%movement}}');
        $this->dropForeignKey('fk-order-client_id','{{%order}}');
        $this->dropForeignKey('fk-ostatok-client_id','{{%ostatok}}');
        $this->dropForeignKey('fk-product-client_id','{{%product}}');
        $this->dropForeignKey('fk-service-client_id','{{%service}}');
        $this->dropForeignKey('fk-shop_brands-client_id','{{%shop_brands}}');
        $this->dropForeignKey('fk-shop_categories-client_id','{{%shop_categories}}');
        $this->dropForeignKey('fk-shop_characteristics-client_id','{{%shop_characteristics}}');
        $this->dropForeignKey('fk-shop_products-client_id','{{%shop_products}}');
        $this->dropForeignKey('fk-shop_tags-client_id','{{%shop_tags}}');
        $this->dropForeignKey('fk-user-client_id','{{%users}}');
        //      переименовываем таблицу
        $this->renameTable('{{%client}}', '{{%clients}}');
        //      Добавляем связи
        $this->addForeignKey('fk-block-client_id','{{%block}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-cash-client_id','{{%cash}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-category-client_id','{{%category}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-client_users-client_id','{{%client_users}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-file-client_id','{{%file}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-movement-client_id','{{%movement}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-order-client_id','{{%order}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-ostatok-client_id','{{%ostatok}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-product-client_id','{{%product}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-service-client_id','{{%service}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_brands-client_id','{{%shop_brands}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_categories-client_id','{{%shop_categories}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_characteristics-client_id','{{%shop_characteristics}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_products-client_id','{{%shop_products}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_tags-client_id','{{%shop_tags}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-users-client_id','{{%users}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //      удаляем связи
        $this->dropForeignKey('fk-block-client_id','{{%block}}');
        $this->dropForeignKey('fk-cash-client_id','{{%cash}}');
        $this->dropForeignKey('fk-category-client_id','{{%category}}');
        $this->dropForeignKey('fk-client_users-client_id','{{%client_users}}');
        $this->dropForeignKey('fk-file-client_id','{{%file}}');
        $this->dropForeignKey('fk-movement-client_id','{{%movement}}');
        $this->dropForeignKey('fk-order-client_id','{{%order}}');
        $this->dropForeignKey('fk-ostatok-client_id','{{%ostatok}}');
        $this->dropForeignKey('fk-product-client_id','{{%product}}');
        $this->dropForeignKey('fk-service-client_id','{{%service}}');
        $this->dropForeignKey('fk-shop_brands-client_id','{{%shop_brands}}');
        $this->dropForeignKey('fk-shop_categories-client_id','{{%shop_categories}}');
        $this->dropForeignKey('fk-shop_characteristics-client_id','{{%shop_characteristics}}');
        $this->dropForeignKey('fk-shop_products-client_id','{{%shop_products}}');
        $this->dropForeignKey('fk-shop_tags-client_id','{{%shop_tags}}');
        $this->dropForeignKey('fk-users-client_id','{{%users}}');
        //      переименовываем таблицу
        $this->renameTable('{{%clients}}', '{{%client}}');
        //      Добавляем связи
        $this->addForeignKey('fk-block-client_id','{{%block}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-cash-client_id','{{%cash}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-category-client_id','{{%category}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-client_user-client_id','{{%client_users}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-file-client_id','{{%file}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-movement-client_id','{{%movement}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-order-client_id','{{%order}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-ostatok-client_id','{{%ostatok}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-product-client_id','{{%product}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-service-client_id','{{%service}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_brands-client_id','{{%shop_brands}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_categories-client_id','{{%shop_categories}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_characteristics-client_id','{{%shop_characteristics}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_products-client_id','{{%shop_products}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-shop_tags-client_id','{{%shop_tags}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
        $this->addForeignKey('fk-user-client_id','{{%users}}','client_id','{{%client}}','id','RESTRICT','RESTRICT');
    }

}
