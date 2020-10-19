<?php

use yii\db\Migration;

/**
 * Class m201019_075756_add_user_roles
 */
class m201019_075756_add_user_roles extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%auth_items}}', ['type', 'name', 'description'], [
            [1, 'user', 'Зарегистрированный пользователь'],
            [1, 'admin', 'Администратор'],
            [1, 'super_admin', 'Супер Админ'],
        ]);

        $this->batchInsert('{{%auth_item_children}}', ['parent', 'child'], [
            ['admin', 'user'],
        ]);

        $this->batchInsert('{{%auth_item_children}}', ['parent', 'child'], [
            ['super_admin', 'admin'],
        ]);

        $this->execute('INSERT INTO {{%auth_assignments}} (item_name, user_id) SELECT \'user\', u.id FROM {{%users}} u ORDER BY u.id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%auth_items}}', ['name' => ['user', 'admin','super_admin']]);
    }

}
