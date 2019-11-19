<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%service}}`.
 */
class m191118_031443_create_service_table extends Migration
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

        $this->createTable('{{%service}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
            'percent' => $this->smallInteger()->unsigned(),
            'is_depend' => $this->boolean()->defaultValue(0),
            'defaultCost'=>$this->integer()->unsigned(),
            'client_id'     => $this->integer()->unsigned(),

        ],$tableOptions);
        $this->addForeignKey(
            'fk-service-client_id',
            '{{%service}}',
            'client_id',
            '{{%client}}',
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
        $this->dropTable('{{%service}}');
    }
}
