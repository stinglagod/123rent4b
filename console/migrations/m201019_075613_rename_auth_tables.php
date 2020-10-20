<?php

use yii\db\Migration;

/**
 * Class m201019_075613_rename_auth_tables
 */
class m201019_075613_rename_auth_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableName = Yii::$app->db->tablePrefix . 'auth_assignments';
        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->renameTable('{{%auth_assignment}}', '{{%auth_assignments}}');
        }
        $tableName = Yii::$app->db->tablePrefix . 'auth_items';
        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->renameTable('{{%auth_item}}', '{{%auth_items}}');
        }
        $tableName = Yii::$app->db->tablePrefix . 'auth_item_children';
        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->renameTable('{{%auth_item_child}}', '{{%auth_item_children}}');
        }
        $tableName = Yii::$app->db->tablePrefix . 'auth_rules';
        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->renameTable('{{%auth_rule}}', '{{%auth_rules}}');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('{{%auth_assignments}}', '{{%auth_assignment}}');
        $this->renameTable('{{%auth_items}}', '{{%auth_item}}');
        $this->renameTable('{{%auth_item_children}}', '{{%auth_item_child}}');
        $this->renameTable('{{%auth_rules}}', '{{%auth_rule}}');
    }

}
