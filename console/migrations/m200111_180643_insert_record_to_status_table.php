<?php

use yii\db\Migration;

/**
 * Class m200111_180643_insert_record_to_status_table
 */
class m200111_180643_insert_record_to_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%status}}', [
            'id'   => 7,
            'name' => 'Частично возращены товары',
            'shortName' => 'Частично возращены товары',
            'hand' => 0,
            'order'=> 5,
        ]);
        $this->update('{{%status}}', [
            'name' => 'Новый',
            'shortName' => 'Новый',
            'hand' => 0,
            'order'=>1,
            'action_id'=>null
        ],
            ['id'=>1]
        );
        $this->update('{{%status}}', [
                'name' => 'Составлена смета',
                'shortName' => 'Составлена смета',
                'hand' => 0,
                'order'=>2,
                'action_id'=>null
        ],
            ['id'=>2]
        );
        $this->update('{{%status}}',
            [
                'name' => 'Частично выданы товары',
                'shortName' => 'Частично выданы товары',
                'hand' => 0,
                'order'=>3,
                'action_id'=>null
            ],
            ['id'=>3]
        );
        $this->update('{{%status}}', [
            'order'=>4
        ],
            ['id'=>4]
        );
        $this->update('{{%status}}', [
            'order'=>6
        ],
            ['id'=>5]
        );
        $this->update('{{%status}}', [
            'hand' =>1,
            'order'=>7
        ],
            ['id'=>6]
        );
        $this->update('{{%status}}', [
            'hand' => 1,
            'order'=>8
        ],
            ['id'=>9]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->update('{{%status}}', [
            'name' => 'Новый',
            'shortName' => 'Новый',
            'hand' => 1,
            'order'=>1,
            'action_id'=>1
        ],['id'=>1]);
        $this->update('{{%status}}', [
            'name' => 'Составлена смета',
            'shortName' => 'Составлена смета',
            'hand' => 1,
            'order'=>2,
            'action_id'=>1
        ],['id'=>2]);
        $this->update('{{%status}}', [
            'name' => 'Утвержден',
            'shortName' => 'Утвержден',
            'hand' => 1,
            'order'=>3,
            'action_id'=>3
        ],['id'=>3]);
        $this->update('{{%status}}', [
            'name' => 'Выдан',
            'shortName' => 'Выдан',
            'hand' => 0,
            'order'=>4
        ],['id'=>4]);
        $this->update('{{%status}}', [
            'name' => 'Возрат',
            'shortName' => 'Возрат',
            'hand' => 0,
            'order'=>5
        ],['id'=>5]);
        $this->update('{{%status}}', [
            'name' => 'Закрыт',
            'shortName' => 'Закрыт',
            'hand' => 0,
            'order'=>6
        ],['id'=>6]);
        $this->update('{{%status}}', [
            'name' => 'Отменен',
            'shortName' => 'Отменен',
            'hand' => 1,
            'order'=>9
        ],['id'=>9]);
        $this->delete('{{%status}}',['id'=>7]);
    }


}
