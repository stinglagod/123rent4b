<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%support_task_file}}`.
 */
class m230412_114220_create_support_task_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        $this->createTable('{{%support_task_files}}', [
            'id' => $this->primaryKey()->unsigned(),
            'file'=>$this->string(),
            'task_id'=>$this->integer()->unsigned(),
            'comment_id'=>$this->integer()->unsigned(),
            'created_at' => $this->integer()->unsigned(),
            'updated_at' => $this->integer()->unsigned(),
            'author_id' => $this->integer()->unsigned(),
            'author_name' => $this->string(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'lastChangeUser_name' => $this->string(),
        ],$tableOptions);

        $this->addForeignKey('{{%fk-support_task_files-author_id}}', '{{%support_task_files}}', 'author_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%fk-support_task_files-lastChangeUser_id}}', '{{%support_task_files}}', 'lastChangeUser_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');

        $this->addForeignKey('{{%fk-support_task_files-task_id}}', '{{%support_task_files}}', 'task_id', '{{%support_tasks}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk-support_task_files-comment_id}}', '{{%support_task_files}}', 'comment_id', '{{%support_task_comments}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%support_task_files}}');
    }
}
