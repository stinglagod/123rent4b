<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 21.10.2019
 * Time: 14:21
 */
use kartik\datecontrol\DateControl;
use kartik\editable\Editable;

/** @var $order \common\models\Order */

if (empty($order)) {
    //      Определяем активный заказ
    $order=\common\models\Order::getActual();
    echo '1';

}
//echo "<pre>"
//print_r($order);exit;
?>

<!-- Start Cart Panel -->
<div class="shopping__cart">

    <div class="shopping__cart__inner">
        <div class="offsetmenu__close__btn">
            <a href="#"><i class="zmdi zmdi-close"></i></a>
        </div>
        <div class="row">
            <?=Editable::widget([
                'model'=>$order,
                'attribute' => 'name',
                'asPopover' => false,
                'value' => 'Номер заказа',
                'header' => 'Заказ',
                'size'=>'sm',
                'options' => ['class'=>'form-control', 'placeholder'=>'Введите название заказа']
            ]);
            ?>
            <br>
            <?=Editable::widget([
                'model'=>$order,
                'attribute' => 'dateBegin',
                'asPopover' => false,
                'value' => 'Дата начала',
                'header' => 'Name',
//                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_DATE,
                'size'=>'sm',
//                'options' => ['class'=>'form-control', 'placeholder'=>'Enter person name...']
            ]);
            ?>
            <?=Editable::widget([
                'model'=>$order,
                'attribute' => 'dateBegin',
                'asPopover' => false,
                'value' => 'Дата окончания',
                'header' => 'Name',
//                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_DATE,
                'size'=>'sm',
//                'options' => ['class'=>'form-control', 'placeholder'=>'Enter person name...']
            ]);
            ?>
        </div>
        <br>
        <div class="shp__cart__wrap">
            <?php
//            TODO: getItems заменить на получить все товары в заказе
            foreach ($order->getItems()->all() as $item){
                /** @var $item \common\models\OrderProduct */

                if (empty($item->product_id)) {
                    continue;
                }
            ?>

            <div class="shp__single__product">
                <div class="shp__pro__thumb">
                    <a href="<?=$item->product->getUrl()?>">
                        <img src="<?=$item->product->getThumb(\common\models\File::THUMBMIDDLE)?>" alt="product images">
                    </a>
                </div>
                <div class="shp__pro__details">
                    <h2><a href="<?=$item->product->getUrl()?>"><?=$item->product->name?></a></h2>
                    <span class="quantity">Кол-во: <?=$item->qty?></span>
                    <span class="shp__price"><?=$item->cost?> <?=$item->getCurrency()?></span>
                </div>
                <div class="remove__btn">
                    <a href="#" title="Remove this item"><i class="zmdi zmdi-close"></i></a>
                </div>
            </div>
            <?php } ?>

        </div>
        <ul class="shoping__total">
            <li class="subtotal">Итого:</li>
            <li class="total__price"><?=$order->getSumm() ?>руб.</li>
        </ul>
        <ul class="shopping__btn">
            <li><a href="cart.html">Просмотр корзины</a></li>
            <li class="shp__checkout"><a href="checkout.html">Оформление заказа</a></li>
        </ul>
    </div>
</div>
