<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop_services}}`.
 */
class m200825_063537_create_shop_services_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB ';

        $this->createTable('{{%shop_services}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name'=>$this->string(),
            'percent'=>$this->float()->unsigned(),
            'is_depend'=>$this->boolean(),
            'defaultCost'=>$this->float()->unsigned(),
            'site_id'=>$this->integer()->unsigned(),
        ], $tableOptions." COMMENT 'Таблица позиций заказа'");

        $this->createIndex('idx-shop_services-site_id','{{%shop_services}}','site_id');
        $this->addForeignKey('fk-shop_services-site_id', '{{%shop_services}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_services}}');
    }
}
