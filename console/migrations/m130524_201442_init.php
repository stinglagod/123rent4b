<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

//======================================================================================================================
// Таблица клиентов
        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
        ],$tableOptions);
//======================================================================================================================
// Таблица пользователей
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->unsigned(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'name' => $this->string(255)->notNull(),
            'surname' => $this->string(255)->notNull(),
            'patronymic' => $this->string(255),
            'telephone' => $this->string(20)->notNull(),
            'client_id'=> $this->integer()->unsigned(),
        ], $tableOptions);
        $this->addForeignKey(
            'fk-user-client_id',
            '{{%user}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

//======================================================================================================================
// Таблица соответсвия пользователей и клиентов
        $this->createTable('{{%client_user}}', [
            'client_id' => $this->integer()->unsigned()->notNull(),
            'user_id' => $this->integer()->unsigned()->notNull(),
        ],$tableOptions);
        $this->addPrimaryKey(
            'pk-client_user',
            '{{%client_user}}',
            ['client_id','user_id']
        );
        $this->createIndex(
            'idx-client_user-client_id',
            '{{%client_user}}',
            'client_id'
        );
        $this->createIndex(
            'idx-client_user-user_id',
            '{{%client_user}}',
            'user_id'
        );
        $this->addForeignKey(
            'fk-client_user-client_id',
            '{{%client_user}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-client_user-user_id',
            '{{%client_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица тип цен
        $this->createTable('{{%priceType}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
        ],$tableOptions);
//======================================================================================================================
// Таблица товара
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
            'description' => $this->string(1024),
            'tag' => $this->string(512),
            'cod' => $this->string(20),
            'primeCost' => $this->double()->unsigned(),
            'cost' => $this->double()->unsigned(),
            'priceType_id' => $this->integer()->unsigned(),
            'is_active' => 'ENUM("active", "inactive", "deleted")',
            'client_id' => $this->integer()->unsigned(),
        ],$tableOptions);
        $sql = "ALTER TABLE {{%product}} ALTER is_active SET DEFAULT 'active'";
        $this->execute($sql);
        $this->addForeignKey(
            'fk-product-priceType_id',
            '{{%product}}',
            'priceType_id',
            '{{%priceType}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-product-client_id',
            '{{%product}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица заказа
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey()->unsigned(),
            'cod' => $this->string(20),
            'dateBegin' => $this->dateTime(),
            'dateEnd' => $this->dateTime(),
            'name' => $this->string(100),
            'customer' => $this->string(255),
            'address' => $this->string(255),
            'description' => $this->string(255),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'autor_id' => $this->integer()->unsigned(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'is_active' => 'ENUM("active", "inactive", "deleted")',
            'client_id' => $this->integer()->unsigned(),
        ],$tableOptions);
        $sql = "ALTER TABLE {{%order}} ALTER is_active SET DEFAULT 'active'";
        $this->execute($sql);
        $this->addForeignKey(
            'fk-order-client_id',
            '{{%order}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-order-autor_id',
            '{{%order}}',
            'autor_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-order-lastChangeUser_id',
            '{{%order}}',
            'lastChangeUser_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица типов периодов
        $this->createTable('{{%periodType}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
        ],$tableOptions);
//======================================================================================================================
// Таблица позиций заказа
        $this->createTable('{{%order_product}}', [
            'id' => $this->primaryKey()->unsigned(),
            'order_id' => $this->integer()->unsigned(),
            'type' => 'ENUM("rent", "sale", "service")',
            'product_id' => $this->integer()->unsigned(),
            'name' => $this->string(100),
            'set' => $this->integer()->unsigned(),      //комплект. Комплекты групируются по этом полю.
            'qty' => $this->integer()->unsigned(),
            'cost' => $this->double()->unsigned(),
            'dateBegin' => $this->dateTime(),
            'dateEnd' => $this->dateTime(),
            'period' => $this->integer()->unsigned(),
            'periodType_id' => $this->integer()->unsigned(),
        ],$tableOptions);
        $this->addForeignKey(
            'fk-order_product-order_id',
            '{{%order_product}}',
            'order_id',
            '{{%order}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-order_product-product_id',
            '{{%order_product}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-order_product-periodType_id',
            '{{%order_product}}',
            'periodType_id',
            '{{%periodType}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица действий с товаром
        $this->createTable('{{%action}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
            'sing' => $this->boolean(true),             //true = '+', false = '-'
            'type' => 'ENUM("move", "rentSoft", "rentHard","repairs")', //move - движение приход, списание, выдача, прием
            //rentSoft - мягкая бронь
            //rentHard - жесткая бронь
            //rentSoft - жесткая бронь
            //repairs - ремонт
        ],$tableOptions);
//======================================================================================================================
// Таблица перемещений
        $this->createTable('{{%movement}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100),
            'dateTime' => $this->dateTime(),
            'qty' => $this->integer(),
            'product_id' => $this->integer()->unsigned(),
            'action_id' => $this->integer()->unsigned(),
            'client_id' => $this->integer()->unsigned(),
            'created_at'=>$this->dateTime(),
            'updated_at'=>$this->dateTime(),
            'autor_id'=>$this->integer()->unsigned(),
            'lastChangeUser_id'=>$this->integer()->unsigned()
        ],$tableOptions);

        $this->addForeignKey(
            'fk-movement-product_id',
            '{{%movement}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-movement-action_id',
            '{{%movement}}',
            'action_id',
            '{{%action}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-movement-autor_id',
            '{{%movement}}',
            'autor_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-movement-lastChangeUser_id',
            '{{%movement}}',
            'lastChangeUser_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-movement-client_id',
            '{{%movement}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица соответсвий перемещения и позиций сайта
        $this->createTable('{{%order_product_action}}', [
            'order_product_id' => $this->integer()->unsigned()->notNull(),
            'movement_id' => $this->integer()->unsigned()->notNull(),
        ],$tableOptions);
        $this->addPrimaryKey(
            'pk-order_product_action',
            '{{%order_product_action}}',
            ['order_product_id','movement_id']
        );
        $this->createIndex(
            'idx-order_product_action-order_product_id',
            '{{%order_product_action}}',
            'order_product_id'
        );
        $this->createIndex(
            'idx-order_product_action-movement_id',
            '{{%order_product_action}}',
            'movement_id'
        );
        $this->addForeignKey(
            'fk-order_product_action-order_product_id',
            '{{%order_product_action}}',
            'order_product_id',
            '{{%order_product}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-order_product_action-movement_id',
            '{{%order_product_action}}',
            'movement_id',
            '{{%movement}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица остатков по товарам
        $this->createTable('{{%ostatok}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dateTime' => $this->dateTime(),
            'qty' => $this->integer(),
            'type' => 'ENUM("move", "rentSoft", "rentHard","repairs")',
            'product_id' => $this->integer()->unsigned(),
            'movement_id' => $this->integer()->unsigned(),
            'client_id' => $this->integer()->unsigned(),
        ],$tableOptions);
        $this->addForeignKey(
            'fk-ostatok-product_id',
            '{{%ostatok}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-ostatok-movement_id',
            '{{%ostatok}}',
            'movement_id',
            '{{%movement}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-ostatok-client_id',
            '{{%ostatok}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица по поступлениям денежных средств
        $this->createTable('{{%cash}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dateTime' => $this->dateTime(),
            'sum' => $this->double(),
            'user_id' => $this->integer()->unsigned(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'client_id' => $this->integer()->unsigned(),
        ],$tableOptions);

        $this->addForeignKey(
            'fk-cash-user_id',
            '{{%cash}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-cash-lastChangeUser_id',
            '{{%cash}}',
            'lastChangeUser_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-cash-client_id',
            '{{%cash}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
// ======================================================================================================================
// Таблица соответсвий движений денежных средств и заказа
        $this->createTable('{{%order_cash}}', [
            'order_id' =>$this->integer()->unsigned()->notNull(),
            'cash_id' => $this->integer()->unsigned()->notNull(),
        ],$tableOptions);
        $this->addPrimaryKey(
            'pk-order_cash',
            '{{%order_cash}}',
            ['order_id','cash_id']
        );
        $this->createIndex(
            'idx-order_cash-order_id',
            '{{%order_cash}}',
            'order_id'
        );
        $this->createIndex(
            'idx-order_cash-cash_id',
            '{{%order_cash}}',
            'cash_id'
        );
        $this->addForeignKey(
            'fk-order_cash-order_id',
            '{{%order_cash}}',
            'order_id',
            '{{%order}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-order_cash-cash_id',
            '{{%order_cash}}',
            'cash_id',
            '{{%cash}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица Файлов
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey()->unsigned(),
            'hash'=>$this->char(32),
            'ext'=>$this->string(4),
            'name'=>$this->string(255),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'autor_id' => $this->integer()->unsigned(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'client_id' => $this->integer()->unsigned(),
        ],$tableOptions);
        $this->addForeignKey(
            'fk-file-autor_id',
            '{{%file}}',
            'autor_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-file-lastChangeUser_id',
            '{{%file}}',
            'lastChangeUser_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-file-client_id',
            '{{%file}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица категорий товаров
        $this->createTable('{{%category}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'autor_id' => $this->integer()->unsigned(),
            'lastChangeUser_id' => $this->integer()->unsigned(),
            'client_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'fk-category-autor_id',
            '{{%category}}',
            'autor_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-category-lastChangeUser_id',
            '{{%category}}',
            'lastChangeUser_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-category-client_id',
            '{{%category}}',
            'client_id',
            '{{%client}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
// Таблица соответсвий товаров и категорий
        $this->createTable('{{%product_category}}', [
            'product_id' => $this->integer()->unsigned()->notNull(),
            'category_id' => $this->bigInteger()->unsigned()->notNull(),
        ],$tableOptions);
        $this->addPrimaryKey(
            'pk-product_category',
            '{{%product_category}}',
            ['product_id','category_id']
        );
        $this->createIndex(
            'idx-product_category-product_id',
            '{{%product_category}}',
            'product_id'
        );
        $this->createIndex(
            'idx-product_category-category_id',
            '{{%product_category}}',
            'category_id'
        );
        $this->addForeignKey(
            'fk-product_category-product_id',
            '{{%product_category}}',
            'product_id',
            '{{%product}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk-product_category-category_id',
            '{{%product_category}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
//======================================================================================================================
//        Добавляем значения по умолчанию
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => 'jDaLk3uPrK3fHV_kT2YMm9WZOrA52TnX',
            'password_hash' => '$2y$13$Ps31ok.Zb1dh16yLo0zvo.8gt2OQieMnDHFqhPWBM14GmjIUvMhrW',
            'email' => 'busenov@ya.ru',
            'status' => '10',
            'created_at' => '1538142852',
            'updated_at' => '1538142852',
        ]);
//      мягкий резерв
        $this->insert('{{%action}}', [
            'id'   => 1,
            'name' => 'Добавление(освобождения)товара в заказ(мягкий резерв)',
            'type' => 'rentSoft',
        ]);
//      жесткий резерв
        $this->insert('{{%action}}', [
            'id'   => 3,
            'name' => 'Получение предоплаты (жесткий резерв)',
            'type' => 'rentHard',
        ]);
//      Выдача(прием) товара
        $this->insert('{{%action}}', [
            'id'   => 5,
            'name' => 'Выдача товара',
            'sing' => false,
            'type' => 'move',
        ]);
        $this->insert('{{%action}}', [
            'id'   => 6,
            'name' => 'Возрат товара',
            'sing' => true,
            'type' => 'move',
        ]);
//      Ремонт
        $this->insert('{{%action}}', [
            'id'   => 7,
            'name' => 'Убытие товара на ремонт',
            'sing' => false,
            'type' => 'repairs',
        ]);
        $this->insert('{{%action}}', [
            'id'   => 8,
            'name' => 'Возрат из ремонта',
            'sing' => true,
            'type' => 'repairs',
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%product_category}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%file}}');
        $this->dropTable('{{%order_cash}}');
        $this->dropTable('{{%cash}}');
        $this->dropTable('{{%ostatok}}');
        $this->dropTable('{{%order_product_action}}');
        $this->dropTable('{{%movement}}');
        $this->dropTable('{{%action}}');
        $this->dropTable('{{%order_product}}');
        $this->dropTable('{{%periodType}}');

        $this->dropTable('{{%order}}');
        $this->dropTable('{{%product}}');

        $this->dropTable('{{%priceType}}');
        $this->dropTable('{{%client_user}}');

        $this->dropTable('{{%user}}');
        $this->dropTable('{{%client}}');


    }
}
