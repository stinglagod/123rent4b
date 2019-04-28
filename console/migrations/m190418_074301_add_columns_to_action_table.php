<?php

use yii\db\Migration;

/**
 * Class m190418_074301_add_columns_to_action_table
 */
class m190418_074301_add_columns_to_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%action}}', 'antipod_id', $this->integer()->unsigned());
        $this->addForeignKey(
            'fk-action-antipod_id',
            '{{%action}}',
            'antipod_id',
            '{{%action}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->insert('{{%action}}', [
            'id'   => 2,
            'name' => 'Освобождение товара от мягкого резерва',
            'sing' => '1',
            'type' =>'rentSoft',
            'shortName'=>'Освобожден от м.резерва',
            'sequence'=>1,
            'order'=>1,
        ]);
        $this->insert('{{%action}}', [
            'id'   => 4,
            'name' => 'Освобождение товара от жеского резерва',
            'sing' => '1',
            'type' =>'rentHard',
            'shortName'=>'Освобожден от брони',
            'sequence'=>3,
            'order'=>3,
        ]);
        $this->update('{{%action}}',[
            'name'=>'Добавление в мягкий резерв',
            'sing'=>0,
            'antipod_id'=>2,
        ],['id'=>1]);
        $this->update('{{%action}}',[
            'name'=>'Жесткий резерв(Бронь)',
            'sing'=>0,
            'antipod_id'=>4,
            'order'=> 2
        ],['id'=>3]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-action-antipod_id','{{%action}}');
        $this->dropColumn('{{%action}}','antipod_id');

        $this->delete('{{%action}}',['id'=>2]);
        $this->delete('{{%action}}',['id'=>4]);

    }


}
