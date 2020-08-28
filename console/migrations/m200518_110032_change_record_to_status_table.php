<?php

use yii\db\Migration;

/**
 * Class m200518_110032_change_record_to_status_table
 */
class m200518_110032_change_record_to_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%status}}', [
            'id'   => 11,
            'name' => 'Новый заказ на сайте',
            'shortName' => 'Новый заказ на сайте',
            'hand' => 0,
            'order'=> 20,
        ]);
        $this->update('{{%status}}', [
            'order'=>10
        ],
            ['id'=>1]
        );
        $this->update('{{%status}}', [
            'order'=>30
        ],
            ['id'=>2]
        );
        $this->update('{{%status}}', [
            'order'=>40
        ],
            ['id'=>3]
        );
        $this->update('{{%status}}', [
            'order'=>50
        ],
            ['id'=>4]
        );
        $this->update('{{%status}}', [
            'order'=>70
        ],
            ['id'=>5]
        );
        $this->update('{{%status}}', [
            'order'=>80
        ],
            ['id'=>6]
        );
        $this->update('{{%status}}', [
            'order'=>60
        ],
            ['id'=>7]
        );
        $this->update('{{%status}}', [
            'order'=>90
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
            'order'=>1
        ],
            ['id'=>1]
        );
        $this->update('{{%status}}', [
            'order'=>2
        ],
            ['id'=>2]
        );
        $this->update('{{%status}}', [
            'order'=>3
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
            'order'=>7
        ],
            ['id'=>6]
        );
        $this->update('{{%status}}', [
            'order'=>5
        ],
            ['id'=>7]
        );
        $this->update('{{%status}}', [
            'order'=>8
        ],
            ['id'=>9]
        );
        $this->delete('{{%status}}',['id'=>11]);
    }

}
