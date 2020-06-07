<?php

use yii\db\Migration;

/**
 * Class m200607_072044_add_client_sites_table
 */
class m200607_072044_add_client_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
//      добавляем таблицу сайтов по клиенту
        $this->createTable('{{%client_sites}}', [
            'id' => $this->primaryKey()->unsigned(),
            'client_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(),
            'status'=> $this->smallInteger()->notNull()->defaultValue(10),
            'telephone'=> $this->string(),
            'address'=> $this->string()
        ], $tableOptions);

//      Добавляем связи для таблицы {{%client_sites}}
        $this->addForeignKey('fk-client_sites-client_id','{{%client_sites}}','client_id','{{%clients}}','id','RESTRICT','RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%client_sites}}');
    }
}
