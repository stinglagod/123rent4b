<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 21.10.2019
 * Time: 14:21
 */
use kartik\editable\Editable;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $order \common\models\Order */


?>

<!-- Start Cart Panel -->
<div class="shopping__cart">

    <div class="shopping__cart__inner">
        <div class="offsetmenu__close__btn">
            <a href="#"><i class="zmdi zmdi-close"></i></a>
        </div>
        <?php Pjax::begin(['id'=>'cart-panel-pjax']); ?>
        <div class="row">
            Аренда с: <br>
            <?=Editable::widget([
                'model'=>$order,
                'attribute' => 'dateBegin',
                'formOptions' =>[
                    'action' =>Url::toRoute(["order/update-ajax",'id'=>$order->id]),
                ],
                'asPopover' => false,
                'value' => 'Дата начала',
                'header' => 'dateBegin',
                'format' => ['date', 'php:d.m.Y'],
                'inputType' => Editable::INPUT_WIDGET,
                'widgetClass' => 'kartik\datecontrol\DateControl',
                'size'=>'sm',
//                'options' => ['class'=>'form-control', 'placeholder'=>'Enter person name...']
            ]);
            ?>
            <br>по: <br>
            <?=Editable::widget([
                'model'=>$order,
                'attribute' => 'dateEnd',
                'formOptions' =>[
                    'action' =>Url::toRoute(["order/update-ajax",'id'=>$order->id]),
                ],
                'asPopover' => false,
                'value' => 'Дата окончания',
                'header' => 'dateEnd',
                'format' => ['date', 'php:d.m.Y'],
                'inputType' => Editable::INPUT_WIDGET,
                'widgetClass' => 'kartik\datecontrol\DateControl',
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
        <?php Pjax::end(); ?>
        <ul class="shopping__btn">
            <li><a href="<?=Url::toRoute(["order/cart"])?>">Просмотр корзины</a></li>
            <li class="shp__checkout"><a href="<?=Url::toRoute(["order/checkout"])?>">Оформление заказа</a></li>
        </ul>

    </div>
</div>
