<?php

use yii\db\Migration;

/**
 * Class m200607_062051_rename_client_user_table
 * Добавляем столбцы
 *
 * Добавляем таблицу
 * client_site
 */
class m200607_062051_rename_client_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //      переименовываем таблицу client_user
        $this->renameTable('{{%client_user}}', '{{%client_users}}');
        //      добавляем столбец в таблицу {{%client_users}}
        $this->addColumn('{{%client_users}}', 'owner', $this->boolean()->defaultValue(null));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%client_users}}', 'owner');
        $this->renameTable('{{%client_users}}', '{{%client_user}}');
    }

}
