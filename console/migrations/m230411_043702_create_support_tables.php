<?php

use yii\db\Migration;

/**
 * Class m230411_043702_create_support_tables
 */
class m230411_043702_create_support_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        ### support_tasks
        $this->createTable('{{%support_tasks}}', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'name' => $this->string(),
            'text' => $this->text(),
            'responsible_id' => $this->integer()->unsigned(),
            'responsible_name' => $this->string(),
            'customer_id' => $this->integer()->unsigned(),
            'customer_name' => $this->string(),
            'status' => $this->smallInteger(),
            'type' => $this->smallInteger(),
            'is_completed' => $this->boolean(),
            'commentClosed' => $this->string(),
            'priority' => $this->smallInteger(),
            'site_id' => $this->integer()->unsigned(),
            'site_name' => $this->string(),
            'client_id' => $this->integer()->unsigned(),
            'client_name' => $this->string(),
            'created_at' => $this->integer()->unsigned(),
            'updated_at' => $this->integer()->unsigned(),
            'author_id' => $this->integer()->unsigned(),
            'author_name' => $this->string(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'lastChangeUser_name' => $this->string(),
        ], $tableOptions);

        $this->addForeignKey('{{%fk-support_tasks-responsible_id}}', '{{%support_tasks}}', 'responsible_id','{{%users}}' , 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%fk-support_tasks-customer_id}}', '{{%support_tasks}}', 'customer_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%fk-support_tasks-author_id}}', '{{%support_tasks}}', 'author_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%fk-support_tasks-lastChangeUser_id}}', '{{%support_tasks}}', 'lastChangeUser_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');

        $this->addForeignKey('{{%fk-support_tasks-site_id}}','{{%support_tasks}}','site_id','{{%client_sites}}','id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%fk-support_tasks-client_id}}','{{%support_tasks}}','client_id','{{%clients}}','id', 'SET NULL', 'CASCADE');

        $this->createIndex('{{%idx-support_tasks-status}}','{{%support_tasks}}','status');
        $this->createIndex('{{%idx-support_tasks-type}}','{{%support_tasks}}','type');
        $this->createIndex('{{%idx-support_tasks-is_completed}}','{{%support_tasks}}','is_completed');

        ### support_tasks_comment
        $this->createTable('{{%support_task_comments}}', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'message' => $this->text(),
            'task_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned(),
            'updated_at' => $this->integer()->unsigned(),
            'author_id' => $this->integer()->unsigned(),
            'author_name' => $this->string(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'lastChangeUser_name' => $this->string(),
        ], $tableOptions);
        $this->addForeignKey('{{%fk-support_task_comments-task_id}}', '{{%support_task_comments}}', 'task_id','{{%support_tasks}}' , 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('{{%fk-support_task_comments-author_id}}', '{{%support_task_comments}}', 'author_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%fk-support_task_comments-lastChangeUser_id}}', '{{%support_task_comments}}', 'lastChangeUser_id', '{{%users}}', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%support_task_comments}}');
        $this->dropTable('{{%support_tasks}}');
    }

}
