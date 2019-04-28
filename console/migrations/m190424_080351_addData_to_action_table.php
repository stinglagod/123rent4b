<?php

use yii\db\Migration;

/**
 * Class m190424_080351_addData_to_action_table
 */
class m190424_080351_addData_to_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%action}}', [
            'id'   => 11,
            'name' => 'Выдача прокатного товара',
            'sing' => '0',
            'shortName'=>'Выдача проката',
            'sequence'=> '3',
            'actionType_id'=>4
        ]);
        $this->insert('{{%action}}', [
            'id'   => 12,
            'name' => 'Возрат прокатного товара',
            'sing' => '1',
            'shortName'=>'Возрат проката',
            'sequence'=> '11',
            'actionType_id'=>4
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%action}}',['id'=>11]);
        $this->delete('{{%action}}',['id'=>12]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190424_080351_addData_to_action_table cannot be reverted.\n";

        return false;
    }
    */
}
