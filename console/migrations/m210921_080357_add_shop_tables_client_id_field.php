<?php

use rent\entities\Client\Site;
use yii\db\Migration;

/**
 * Class m210921_080357_add_shop_tables_client_id_field
 */
class m210921_080357_add_shop_tables_client_id_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //shop_characteristics
        $this->alterColumn('{{%shop_characteristics}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_characteristics}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_characteristics-client_id}}', '{{%shop_characteristics}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_characteristics-client_id}}', '{{%shop_characteristics}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_orders
        $this->alterColumn('{{%shop_orders}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_orders}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_orders-client_id}}', '{{%shop_orders}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_orders-client_id}}', '{{%shop_orders}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_payments
        $this->alterColumn('{{%shop_payments}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_payments}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_payments-client_id}}', '{{%shop_payments}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_payments-client_id}}', '{{%shop_payments}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_balance_cash
        $this->alterColumn('{{%shop_balance_cash}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_balance_cash}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_balance_cash-client_id}}', 'shop_balance_cash', 'client_id');
        $this->addForeignKey('{{%fk-shop_balance_cash-client_id}}', '{{%shop_balance_cash}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_balance
        $this->alterColumn('{{%shop_balance}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_balance}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_balance-client_id}}', '{{%shop_balance}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_balance-client_id}}', '{{%shop_balance}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_item_blocks
        $this->alterColumn('{{%shop_item_blocks}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_item_blocks}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_item_blocks-client_id}}', '{{%shop_item_blocks}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_item_blocks-client_id}}', '{{%shop_item_blocks}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_movements
        $this->alterColumn('{{%shop_movements}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_movements}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_movements-client_id}}', '{{%shop_movements}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_movements-client_id}}', '{{%shop_movements}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_order_items
        $this->alterColumn('{{%shop_order_items}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_order_items}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_order_items-client_id}}', '{{%shop_order_items}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_order_items-client_id}}', '{{%shop_order_items}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_services
        $this->alterColumn('{{%shop_services}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_services}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_services-client_id}}', '{{%shop_services}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_services-client_id}}', '{{%shop_services}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_brands
        $this->alterColumn('{{%shop_brands}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%shop_brands}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-shop_brands-client_id}}', '{{%shop_brands}}', 'client_id');
        $this->addForeignKey('{{%fk-shop_brands-client_id}}', '{{%shop_brands}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //pages
        $this->alterColumn('{{%pages}}', 'site_id', $this->integer()->unsigned());
        $this->addColumn('{{%pages}}', 'client_id', $this->integer()->unsigned());
        $this->createIndex('{{%idx-pages-client_id}}', '{{%pages}}', 'client_id');
        $this->addForeignKey('{{%fk-pages-client_id}}', '{{%pages}}', 'client_id', '{{%clients}}', 'id', 'CASCADE', 'RESTRICT');

        //shop_categories
        $this->alterColumn('{{%shop_categories}}', 'site_id', $this->integer()->unsigned());

        //shop_products
        $this->alterColumn('{{%shop_products}}', 'site_id', $this->integer()->unsigned());

        $this->siteIdToClientId();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //shop_characteristics
//        $this->alterColumn('{{%shop_characteristics}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_characteristics-client_id}}', '{{%shop_characteristics}}');
        $this->dropIndex('{{%idx-shop_characteristics-client_id}}', '{{%shop_characteristics}}');
        $this->dropColumn('{{%shop_characteristics}}', 'client_id');

        //shop_orders
//        $this->alterColumn('{{%shop_orders}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_orders-client_id}}', '{{%shop_orders}}');
        $this->dropIndex('{{%idx-shop_orders-client_id}}', '{{%shop_orders}}');
        $this->dropColumn('{{%shop_orders}}', 'client_id');

        //shop_payments
//        $this->alterColumn('{{%shop_payments}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_payments-client_id}}', '{{%shop_payments}}');
        $this->dropIndex('{{%idx-shop_payments-client_id}}', '{{%shop_payments}}');
        $this->dropColumn('{{%shop_payments}}', 'client_id');

        //shop_balance_cash
//        $this->alterColumn('{{%shop_balance_cash}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_balance_cash-client_id}}', '{{%shop_balance_cash}}');
        $this->dropIndex('{{%idx-shop_balance_cash-client_id}}', '{{%shop_balance_cash}}');
        $this->dropColumn('{{%shop_balance_cash}}', 'client_id');

        //shop_balance
//        $this->alterColumn('{{%shop_balance}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_balance-client_id}}', '{{%shop_balance}}');
        $this->dropIndex('{{%idx-shop_balance-client_id}}', '{{%shop_balance}}');
        $this->dropColumn('{{%shop_balance}}', 'client_id');

        //shop_item_blocks
//        $this->alterColumn('{{%shop_item_blocks}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_item_blocks-client_id}}', '{{%shop_item_blocks}}');
        $this->dropIndex('{{%idx-shop_item_blocks-client_id}}', '{{%shop_item_blocks}}');
        $this->dropColumn('{{%shop_item_blocks}}', 'client_id');

        //shop_movements
//        $this->alterColumn('{{%shop_movements}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_movements-client_id}}', '{{%shop_movements}}');
        $this->dropIndex('{{%idx-shop_movements-client_id}}', '{{%shop_movements}}');
        $this->dropColumn('{{%shop_movements}}', 'client_id');

        //shop_order_items
//        $this->alterColumn('{{%shop_order_items}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_order_items-client_id}}', '{{%shop_order_items}}');
        $this->dropIndex('{{%idx-shop_order_items-client_id}}', '{{%shop_order_items}}');
        $this->dropColumn('{{%shop_order_items}}', 'client_id');

        //shop_services
//        $this->alterColumn('{{%shop_services}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_services-client_id}}', '{{%shop_services}}');
        $this->dropIndex('{{%idx-shop_services-client_id}}', '{{%shop_services}}');
        $this->dropColumn('{{%shop_services}}', 'client_id');

        //shop_brands
//        $this->alterColumn('{{%shop_brands}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-shop_brands-client_id}}', '{{%shop_brands}}');
        $this->dropIndex('{{%idx-shop_brands-client_id}}', '{{%shop_brands}}');
        $this->dropColumn('{{%shop_brands}}', 'client_id');

        //pages
//        $this->alterColumn('{{%pages}}', 'site_id', $this->integer()->unsigned()->notNull());
        $this->dropForeignKey('{{%fk-pages-client_id}}', '{{%pages}}');
        $this->dropIndex('{{%idx-pages-client_id}}', '{{%pages}}');
        $this->dropColumn('{{%pages}}', 'client_id');

        //shop_categories
//        $this->alterColumn('{{%shop_categories}}', 'site_id', $this->integer()->unsigned()->notNull());

        //shop_products
//        $this->alterColumn('{{%shop_products}}', 'site_id', $this->integer()->unsigned()->notNull());
    }

    private function siteIdToClientId()
    {

        echo "Установка клиента на основании сайта".PHP_EOL;
        //shop_characteristics
        echo "Характеристик: ".$this->setClientId(\rent\entities\Shop\Characteristic::find(true)->all()).PHP_EOL;
        //shop_orders
        echo "Заказов: ".$this->setClientId(\rent\entities\Shop\Order\Order::find(true)->all()).PHP_EOL;
        //shop_payments
        echo "Оплат: ".$this->setClientId(\rent\entities\Shop\Order\Payment::find(true)->all()).PHP_EOL;
        //shop_balance_cash
        echo "Баланса денег: ".$this->setClientId(\rent\entities\Shop\Order\BalanceCash::find(true)->all()).PHP_EOL;
        //shop_balance
        echo "Баланса наличия: ".$this->setClientId(\rent\entities\Shop\Product\Movement\Balance::find(true)->all()).PHP_EOL;
        //shop_item_blocks
        echo "Наименования блоков: ".$this->setClientId(\rent\entities\Shop\Order\Item\ItemBlock::find(true)->all()).PHP_EOL;
        //shop_movements
        echo "Движения: ".$this->setClientId(\rent\entities\Shop\Product\Movement\Movement::find(true)->all()).PHP_EOL;
        //shop_order_items
        echo "Позиций заказа: ".$this->setClientId(\rent\entities\Shop\Order\Item\OrderItem::find(true)->all()).PHP_EOL;
        //shop_services
        echo "Услуг: ".$this->setClientId(\rent\entities\Shop\Service::find(true)->all()).PHP_EOL;
        //shop_brands
        echo "Брендов: ".$this->setClientId(\rent\entities\Shop\Brand::find(true)->all()).PHP_EOL;

    }
    private function setClientId(array $entities):int
    {
        $num=0;
        foreach ($entities as $entity) {
            if (empty($entity->client_id)) {
                $site=Site::find(true)->where(['id'=>$entity->site_id])->one();

                $this->update($entity->tableName(),['client_id'=>$site->client_id],['id'=>$entity->id]);
                $num++;
            }
        }
        return $num;
    }
}
