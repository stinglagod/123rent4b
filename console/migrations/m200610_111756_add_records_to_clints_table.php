<?php

use yii\db\Migration;

/**
 * Class m200610_111756_add_records_to_clints_table
 */
class m200610_111756_add_records_to_clints_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('{{%clients}}', [
            'name' => 'Rent4b',
            'created_at'=>time(),
            'updated_at'=>time(),
            'status'=>10
        ],
            ['id'=>1]
        );
        $this->delete('{{%client_sites}}',['id'=>1]);
        $this->insert('{{%client_sites}}', [
            'id' => 1,
            'name' => 'Главный сайт',
            'domain' => 'rent4b.ru',
            'status'=>10,
            'client_id'=>1,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
        $this->delete('{{%client_sites}}',['client_id'=>2]);
        $this->delete('{{%clients}}',['id'=>2]);
        $this->insert('{{%clients}}', [
            'id' => 2,
            'name' => 'Свадебная фея',
            'created_at'=>time(),
            'updated_at'=>time(),
            'status'=>10
        ]);

        $this->insert('{{%client_sites}}', [
            'id' => 2,
            'name' => 'Свадебная фея',
            'domain' => 'feya.rent4b.ru',
            'status'=>10,
            'client_id'=>2,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200610_111756_add_records_to_clints_table cannot be reverted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200610_111756_add_records_to_clints_table cannot be reverted.\n";

        return false;
    }
    */
}
