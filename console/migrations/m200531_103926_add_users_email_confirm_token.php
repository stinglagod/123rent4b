<?php

use yii\db\Migration;

/**
 * Class m200531_103926_add_users_email_confirm_token
 */
class m200531_103926_add_users_email_confirm_token extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'email_confirm_token', $this->string()->unique()->after('email'));
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'email_confirm_token');
    }
}
