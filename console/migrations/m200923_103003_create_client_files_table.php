<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client_files}}`.
 */
class m200923_103003_create_client_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%client_files}}', [
            'id' => $this->primaryKey()->unsigned(),
            'file' => $this->string()->notNull(),
            'ext' => $this->string(),
            'site_id'=>$this->integer()->unsigned(),
        ], $tableOptions);

        $this->createIndex('{{%idx-client_files-site_id}}', '{{%client_files}}', 'site_id');

        $this->addForeignKey('{{%fk-client_files-site_id}}', '{{%client_files}}', 'site_id', '{{%client_sites}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%client_files}}');
    }
}
