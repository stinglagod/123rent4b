<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cashType}}`.
 */
class m190328_060629_create_cashType_table extends Migration
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

        $this->createTable('{{%cashType}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),

        ],$tableOptions);


        $this->addColumn('{{%cash}}', 'created_at', $this->dateTime());
        $this->addColumn('{{%cash}}', 'updated_at', $this->dateTime());
        $this->addColumn('{{%cash}}', 'cashType_id', $this->integer()->unsigned());
        $this->addColumn('{{%cash}}', 'note', $this->string(255));
        $this->addColumn('{{%cash}}', 'payer', $this->string(255));


        $this->addForeignKey(
            'fk-cash-cashType_id',
            '{{%cash}}',
            'cashType_id',
            '{{%cashType}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//        не нравиться название переименовываю:)
        $this->dropForeignKey('fk-cash-user_id','{{%cash}}');
        $this->renameColumn('{{%cash}}', 'user_id','autor_id');
        $this->addForeignKey(
            'fk-cash-autor_id',
            '{{%cash}}',
            'autor_id',
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
        //        не нравиться название переименовываю:)
        $this->dropForeignKey('fk-cash-autor_id','{{%cash}}');
        $this->renameColumn('{{%cash}}', 'autor_id','user_id');
        $this->addForeignKey(
            'fk-cash-user_id',
            '{{%cash}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->dropForeignKey('fk-cash-cashType_id','{{%cash}}');

        $this->dropColumn('{{%cash}}', 'created_at');
        $this->dropColumn('{{%cash}}', 'updated_at');
        $this->dropColumn('{{%cash}}', 'cashType_id');
        $this->dropColumn('{{%cash}}', 'note');
        $this->dropColumn('{{%cash}}', 'payer');

        $this->dropTable('{{%cashType}}');
    }
}
