<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pages}}`.
 */
class m201016_045431_create_pages_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%pages}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'content' => 'MEDIUMTEXT',
            'meta_json' => 'JSON NOT NULL',
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'site_id'=>$this->integer()->unsigned(),
        ], $tableOptions);

        $this->createIndex('{{%idx-pages-slug}}', '{{%pages}}', ['slug','site_id'], true);

        $this->createIndex('{{%idx-pages-site_id}}', '{{%pages}}', 'site_id');

        $this->addForeignKey('{{%fk-pages-site_id}}', '{{%pages}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE');

        $this->insert('{{%pages}}', [
            'id' => 1,
            'title' => '',
            'slug' => 'root',
            'content' => null,
            'meta_json' => '{}',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
            'site_id' => 1,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%pages}}');
    }
}
