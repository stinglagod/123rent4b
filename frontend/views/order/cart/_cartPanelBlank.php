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
            Для добавления в корзину необходимо указать даты заказа <br>
            Укажите даты:

            <br>
            <?php
            echo DatePicker::widget([
                'model' => $order,
                'attribute' => 'dateBegin',
                'attribute2' => 'dateEnd',
                'options' => ['placeholder' => 'Начало'],
                'options2' => ['placeholder' => 'Конец'],
                'type' => DatePicker::TYPE_RANGE,
                'form' => $form,
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy ',
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'todayBtn' => true,
                ]
            ]);
            ?>
            <br>

            Дата начала:<br>
            <?=Editable::widget([
                'model'=>$order,
                'attribute' => 'dateBegin',
                'formOptions' =>[
                    'action' =>Url::toRoute(["order/update-ajax"]),
                ],
                'asPopover' => false,
                'value' => 'Дата начала',
                'header' => 'dateBegin',
//                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_DATE,
                'size'=>'sm',
//                'options' => ['class'=>'form-control', 'placeholder'=>'Enter person name...']
            ]);
            ?>
            <br>
            Дата окончания:<br>
            <?=Editable::widget([
                'model'=>$order,
                'attribute' => 'dateEnd',
                'formOptions' =>[
                    'action' =>Url::toRoute(["order/update-ajax"]),
                ],
                'asPopover' => false,
                'value' => 'Дата окончания',
                'header' => 'dateEnd',
//                'format' => Editable::FORMAT_BUTTON,
                'inputType' => Editable::INPUT_DATE,
                'size'=>'sm',
//                'options' => ['class'=>'form-control', 'placeholder'=>'Enter person name...']
            ]);
            ?>
<!--            <ul class="shopping__btn">-->
<!--                <li><a href="#" class="createNewOrder" data-url="--><?//=Url::toRoute("order/update-ajax");?><!--">Создать заказ</a></li>-->
<!--            </ul>-->
        </div>
        <br>

    </div>
</div>
