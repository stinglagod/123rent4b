<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status}}`.
 */
class m190415_051329_create_status_table extends Migration
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

        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name'=> $this->string(100),
            'shortName'=> $this->string(100),
            'hand'=> $this->boolean()->defaultValue(0),
            'order'=>$this->integer()->unsigned(),
            'action_id'=>$this->integer()->unsigned(),
        ],$tableOptions);
        $this->addForeignKey(
            'fk-status-action_id',
            '{{%status}}',
            'action_id',
            '{{%action}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        //        =======================добавляем значения======================
        $this->insert('{{%status}}', [
            'id'   => 1,
            'name' => 'Новый',
            'shortName' => 'Новый',
            'hand' => 1,
            'order'=>1,
            'action_id'=>1
        ]);
        $this->insert('{{%status}}', [
            'id'   => 2,
            'name' => 'Составлена смета',
            'shortName' => 'Составлена смета',
            'hand' => 1,
            'order'=>2,
            'action_id'=>1
        ]);
        $this->insert('{{%status}}', [
            'id'   => 3,
            'name' => 'Утвержден',
            'shortName' => 'Утвержден',
            'hand' => 1,
            'order'=>3,
            'action_id'=>3
        ]);
        $this->insert('{{%status}}', [
            'id'   => 4,
            'name' => 'Выдан',
            'shortName' => 'Выдан',
            'hand' => 0,
            'order'=>4
        ]);
        $this->insert('{{%status}}', [
            'id'   => 5,
            'name' => 'Возрат',
            'shortName' => 'Возрат',
            'hand' => 0,
            'order'=>5
        ]);
        $this->insert('{{%status}}', [
            'id'   => 6,
            'name' => 'Закрыт',
            'shortName' => 'Закрыт',
            'hand' => 0,
            'order'=>6
        ]);
        $this->insert('{{%status}}', [
            'id'   => 9,
            'name' => 'Отменен',
            'shortName' => 'Отменен',
            'hand' => 1,
            'order'=>9
        ]);

        $this->dropForeignKey('fk-order-status_id','{{%order}}');
        $this->dropColumn('{{%order}}', 'status_id');
        $this->addColumn('{{%order}}', 'status_id', $this->integer()->unsigned()->defaultValue(1));
        $this->addForeignKey(
            'fk-order-status_id',
            '{{%order}}',
            'status_id',
            '{{%status}}',
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


        $this->dropForeignKey('fk-order-status_id','{{%order}}');
        $this->dropColumn('{{%order}}', 'status_id');
        $this->addColumn('{{%order}}', 'status_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-order-status_id',
            '{{%order}}',
            'status_id',
            '{{%action}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->dropTable('{{%status}}');
    }
}
