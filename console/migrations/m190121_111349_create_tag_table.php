<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tag`.
 */
class m190121_111349_create_tag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(30),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'autor_id' => $this->integer()->unsigned(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'client_id' => $this->integer()->unsigned(),
        ],$tableOptions);
        $this->createIndex(
            'idx-tag-name',
            '{{%tag}}',
            'name',
            true
        );
        $this->addForeignKey(
            'fk-tag-client_id',
            '{{%tag}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-tag-autor_id',
            '{{%tag}}',
            'autor_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-tag-lastChangeUser_id',
            '{{%tag}}',
            'lastChangeUser_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tag}}');
    }
}
