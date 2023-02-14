<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%crm_contacts}}`.
 */
class m230214_033043_create_crm_contacts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%crm_contacts}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
            'surname' => $this->string(100),
            'patronymic' => $this->string(100),
            'status' => $this->integer(),
            'telephone' => $this->string(15),
            'email' => $this->string(254),
            'note' => $this->string(254),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'author_id' => $this->integer()->unsigned(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'client_id' => $this->integer()->unsigned(),
        ]);

        $this->addColumn('{{%shop_orders}}','contact_id',$this->integer()->unsigned());
        $this->addForeignKey('{{%fk-shop_orders-contact_id}}','{{%shop_orders}}','contact_id','{{%crm_contacts}}','id');

        $this->addForeignKey('{{%fk-crm_contacts-author_id}}','{{%crm_contacts}}','author_id','{{%users}}','id');
        $this->addForeignKey('{{%fk-crm_contacts-lastChangeUser_id}}','{{%crm_contacts}}','lastChangeUser_id','{{%users}}','id');

        $this->addForeignKey('{{%fk-crm_contacts-client_id}}','{{%crm_contacts}}','client_id','{{%clients}}','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-shop_orders-contact_id}}','{{%shop_orders}}');
        $this->dropColumn('{{%shop_orders}}','contact_id');
        $this->dropTable('{{%crm_contacts}}');
    }
}
