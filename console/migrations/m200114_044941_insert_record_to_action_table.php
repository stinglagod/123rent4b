<?php

use yii\db\Migration;

/**
 * Class m200114_044941_insert_record_to_action_table
 */
class m200114_044941_insert_record_to_action_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%action}}',[
            'id'=>3,
            'name'=>'Бронь',
            'sing'=>0,
            'shortName'=>'Бронь',
            'sequence'=>'',
            'order'=>2,
            'antipod_id'=>4,
            'actionType_id'=>2
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>4,
            'name'=>'Освобожден от брони',
            'sing'=>1,
            'shortName'=>'Освобожден от брони',
            'sequence'=>3,
            'order'=>3,
            'antipod_id'=>null,
            'actionType_id'=>2
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>5,
            'name'=>'Выдача продажного товара',
            'sing'=>0,
            'shortName'=>'Выдача',
            'sequence'=>'',
            'order'=>null,
            'antipod_id'=>null,
            'actionType_id'=>3
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>6,
            'name'=>'УДАЛИТЬВозрат продажного товара',
            'sing'=>1,
            'shortName'=>'УДАЛИТЬВозрат',
            'sequence'=>'',
            'order'=>null,
            'antipod_id'=>null,
            'actionType_id'=>3
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>7,
            'name'=>'Убытие товара на ремонт',
            'sing'=>0,
            'shortName'=>'Ремонт',
            'sequence'=>'',
            'order'=>null,
            'antipod_id'=>null,
            'actionType_id'=>5
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>8,
            'name'=>'Возрат из ремонта',
            'sing'=>1,
            'shortName'=>'Отремонтировано',
            'sequence'=>'',
            'order'=>null,
            'antipod_id'=>null,
            'actionType_id'=>5
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>9,
            'name'=>'Приход товара на склад',
            'sing'=>1,
            'shortName'=>'Поступление',
            'sequence'=>'',
            'order'=>null,
            'antipod_id'=>null,
            'actionType_id'=>3
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>10,
            'name'=>'Уход товара со склада',
            'sing'=>0,
            'shortName'=>'Списание',
            'sequence'=>'',
            'order'=>null,
            'antipod_id'=>null,
            'actionType_id'=>3
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>11,
            'name'=>'Выдача прокатного товара',
            'sing'=>0,
            'shortName'=>'Списание',
            'sequence'=>3,
            'order'=>null,
            'antipod_id'=>null,
            'actionType_id'=>4
        ],true);
        $this->upsert('{{%action}}',[
            'id'=>12,
            'name'=>'Возрат прокатного товара',
            'sing'=>1,
            'shortName'=>'Списание',
            'sequence'=>11,
            'order'=>null,
            'antipod_id'=>null,
            'actionType_id'=>4
        ],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200114_044941_insert_record_to_action_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200114_044941_insert_record_to_action_table cannot be reverted.\n";

        return false;
    }
    */
}
