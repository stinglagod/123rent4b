<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 21.10.2019
 * Time: 14:21
 */
use \yii\helpers\Url;
use kartik\editable\Editable;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

/** @var $order \common\models\Order */



?>

<!-- Start Cart Panel -->
<div class="shopping__cart">

    <div class="shopping__cart__inner">
        <div class="offsetmenu__close__btn">
            <a href="#"><i class="zmdi zmdi-close"></i></a>
        </div>
        <div class="row">
            <pre>
                <?=(date("Y-m-d 00:00:00"));?>
            </pre>
            <?php if (!Yii::$app->user->isGuest) { ?>
            Заказ: <?=$order->isNewRecord?"<Новый>":$order->name?> <br>
            Аренда с: <br>
            <?=Editable::widget([
                'model'=>$order,
                'attribute' => 'dateBegin',
                'formOptions' =>[
                    'action' =>Url::toRoute(["order/update-ajax"]),
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
                    'action' =>Url::toRoute(["order/update-ajax"]),
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
            <?php } else {?>
                Для добавления в корзину необходимо авторизироваться:
            <?php } ?>
<!--            <ul class="shopping__btn">-->
<!--                <li><a href="#" class="createNewOrder" data-url="--><?//=Url::toRoute("order/update-ajax");?><!--">Создать заказ</a></li>-->
<!--            </ul>-->
        </div>
        <br>

    </div>
</div>
