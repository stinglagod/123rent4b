<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%actionType}}`.
 */
class m190423_055234_create_actionType_table extends Migration
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

        $this->createTable('{{%actionType}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name'=>$this->string(100),
            'shortName'=>$this->string(50),
        ],$tableOptions);

        $this->addColumn('{{%action}}', 'actionType_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-action-actionType_id',
            '{{%action}}',
            'actionType_id',
            '{{%actionType}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addColumn('{{%ostatok}}', 'actionType_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-ostatok-actionType_id',
            '{{%ostatok}}',
            'actionType_id',
            '{{%actionType}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

//      Значения обязательные

        $this->insert('{{%actionType}}', [
            'id'   => 1,
            'name' => 'Мягкий резерв',
            'shortName'=>'reservSoft',
        ]);
        $this->insert('{{%actionType}}', [
            'id'   => 2,
            'name' => 'Жесткий резерв (бронь)',
            'shortName'=>'reservSoft',
        ]);
        $this->insert('{{%actionType}}', [
            'id'   => 3,
            'name' => 'Перемещение',
            'shortName'=>'move',
        ]);
        $this->insert('{{%actionType}}', [
            'id'   => 4,
            'name' => 'Прокат',
            'shortName'=>'rent',
        ]);
        $this->insert('{{%actionType}}', [
            'id'   => 5,
            'name' => 'Ремонт',
            'shortName'=>'repairs',
        ]);


        $this->update('{{%action}}',[
            'actionType_id'=>1,
        ],['id'=>1]);
        $this->update('{{%action}}',[
            'actionType_id'=>1,
        ],['id'=>2]);
        $this->update('{{%action}}',[
            'actionType_id'=>2,
        ],['id'=>3]);
        $this->update('{{%action}}',[
            'actionType_id'=>2,
        ],['id'=>4]);
        $this->update('{{%action}}',[
            'actionType_id'=>4,
        ],['id'=>5]);
        $this->update('{{%action}}',[
            'actionType_id'=>4,
        ],['id'=>6]);
        $this->update('{{%action}}',[
            'actionType_id'=>5,
        ],['id'=>7]);
        $this->update('{{%action}}',[
            'actionType_id'=>5,
        ],['id'=>8]);
        $this->update('{{%action}}',[
            'actionType_id'=>3,
        ],['id'=>9]);
        $this->update('{{%action}}',[
            'actionType_id'=>3,
        ],['id'=>10]);

        $this->update('{{%ostatok}}',[
            'actionType_id'=>3
        ],['type'=>'move']);

        $this->dropColumn('{{%action}}','type');
        $this->dropColumn('{{%ostatok}}','type');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->addColumn('{{%action}}','type','ENUM("move", "rentSoft", "rentHard","repairs")');
        $this->addColumn('{{%ostatok}}','type','ENUM("move", "rentSoft", "rentHard","repairs")');
        $this->dropForeignKey('fk-action-actionType_id','{{%action}}');
        $this->dropColumn('{{%action}}','actionType_id');
        $this->dropForeignKey('fk-ostatok-actionType_id','{{%ostatok}}');
        $this->dropColumn('{{%ostatok}}','actionType_id');
        $this->dropTable('{{%actionType}}');
    }
}
