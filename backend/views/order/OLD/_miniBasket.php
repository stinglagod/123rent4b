<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 11.12.2018
 * Time: 11:37
 */
use yii\helpers\Url;
/* @var $orders \common\models\Order[] */
/* @var $activeOrder \common\models\Order */
$session = Yii::$app->session;
$activeOrder=(empty($session['activeOrderId']))?reset($orders):$orders[$session['activeOrderId']];
$count=count($orders);
//print_r($session['activeOrderId']);exit;
?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <?=$activeOrder->name?>
    <span class="label label-success"><?=$count?></span>
    <i class="fa fa-toggle-down"></i>
</a>
<ul class="dropdown-menu">
    <li class="header">У вас <?=$count?> заказ(a)(ов) в работе</li>
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
                                <i class='fa fa-cube text-yellow'></i> $order->name  
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
