<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 11.12.2018
 * Time: 15:06
 */
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $orders \common\models\Order[] */
/* @var $activeOrder \common\models\Order */

//TODO: перенести в контроллер
$orders=\common\models\Order::getActual();
$session = Yii::$app->session;
$activeOrder=(empty($session['activeOrderId']))?reset($orders):$orders[$session['activeOrderId']];
$countOrders=count($orders);
$countProducts=count($activeOrder->orderProducts)
?>
<li class="dropdown notifications-menu" id="orderList">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <b>Активный заказ:</b> <?=$activeOrder->name?>
        <span class="label label-success"><?=$countOrders?></span>
        <i class="fa fa-toggle-down"></i>
    </a>
    <ul class="dropdown-menu">
        <li class="header">У вас <?=$countOrders?> заказ(a)(ов) в работе</li>
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu">
                <?php
                $i=15;
                foreach ($orders as $order) {
                    $i--;
                    echo "
                        <li>
                            <a href='#' class='orderItem' data-id='$order->id' id='order_$order->id'>
                                <i class='fa fa-cube text-yellow'></i> $order->name    <i class='fa fa-gear text-yellow settingsOrder' data-id='$order->id'></i>
                            </a>
                        </li>        
                        ";
                    if ($i==0) break;
                }
                ?>
            </ul>
        </li>
        <li class="footer"><a href="#" class="createNewOrder"><i class="fa fa-sticky-note-o text-aqua"></i> (Создать новый заказ)</a><a href="<?=Url::toRoute("order/index");?>">Посмотреть все заказы</a></li>
    </ul>
</li>
<li class="dropdown notifications-menu" id="miniBasket">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-shopping-cart"></i>
        <span class="label label-warning"><?=$countProducts?></span>
    </a>
    <ul class="dropdown-menu">
        <?php
            if ($countProducts==0) {
                echo '<li class="header">В корзине нет товаров</li>';
            }
        ?>

        <li>
            <ul class="menu">
            <?php
                foreach ($activeOrder->orderProducts as $orderProduct) {
                    /* @var $orderProduct \common\models\OrderProduct */
                    echo "
                        <li>
                            <a href='#' class='basketItem' data-id='$orderProduct->id' id='orderProduct_$orderProduct->id'>
                                ".Html::img($orderProduct->product->getThumb(\common\models\File::THUMBSMALL)) ." ".$orderProduct->product->name.": ". $orderProduct->qty ." шт.    
                            </a>
                        </li>        
                        ";
                }
            ?>

            </ul>
        </li>
        <li class="footer"><a href="<?=Url::toRoute(["order/update",'id'=>$activeOrder->id]);?>">Перейти к корзине</a></li>
        </li>
    </ul>
</li>

