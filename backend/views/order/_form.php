<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\select2\Select2;
use common\models\Action;
use kartik\dialog\Dialog;
use yii\web\JsExpression;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
$selectOperations= Select2::widget([
    'name' => 'status',
    'hideSearch' => true,
    'data' => [
        Action::ISSUE => 'Выдать отмеченные',
        Action::RETURN => 'Получить отмеченные',
        Action::TOREPAIR => 'Отправить в ремонт',
        99 => 'Удалить отмеченные'
    ],
    'options' => ['placeholder' => 'Выберите операцию'],
    'pluginOptions' => [
        'allowClear' => true,
//        'width' => '30%'
    ],
    'pluginEvents' => [
        "select2:selecting" => "function(e) { changeOperation(e) }",
    ],
]);
?>
<div class="user-index box box-primary">
    <?php Pjax::begin(); ?>
<?= GridView::widget([
    'id' => 'order-grid',
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{summary}\n{pager}",
    'pjax' => true,
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'header' => '',
        ],
        [
            'attribute' => 'type',
            'group'=>true,
        ],
        [
            'attribute' => 'product_id',
            'pageSummary' => 'Итого',
            'headerOptions' => ['class' => 'text-center'],
            'width' => '9%',
            'vAlign' => 'middle',
            'value' => function (\common\models\OrderProduct $data) {
                return $data->product->name;
            },
            'format' => 'raw',
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'cost',
            'format' => ['decimal', 2],
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Цена'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'step' => 100, 'decimals' => 2]
                ],
                'pjaxContainerId' => 'pjax_movement_grid',
                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
            ],
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'qty',
            'format' => ['decimal', 0],
            'pageSummary' => true,
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Количество'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
//                        TODO: Добавить максимум по кол-ву свободного товара на даты аренды
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ],
                'pjaxContainerId' => 'pjax_movement_grid',
                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
            ],
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'period',
            'format' => ['decimal', 0],
            'pageSummary' => true,
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Период сдачи'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
//                        TODO: Добавить максимум по кол-ву свободного товара на даты аренды
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ],
                'pjaxContainerId' => 'pjax_movement_grid',
                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
            ],
        ],

        [
            'class' => 'kartik\grid\FormulaColumn',
            'header' => 'Сумма',
            'vAlign' => 'middle',
            'value' => function ($model, $key, $index, $widget) {
                $p = compact('model', 'key', 'index');
                return $widget->col(2, $p) * $widget->col(3, $p) * $widget->col(4, $p);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'hAlign' => 'right',
//            'width' => '20%',
            'format' => ['decimal', 2],
            'mergeHeader' => true,
            'pageSummary' => true,
            'footer' => true
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'dateBegin',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '9%',
            'format' => ['date', 'php:d.m.Y'],
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Дата начала аренды'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
                'widgetClass' =>  'kartik\datecontrol\DateControl',
                'options' => [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                ],
//                'pjaxContainerId' => 'pjax_movement_grid',
                'formOptions' => [ 'action' => Url::toRoute(['order/update-ajax']) ],
            ],
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'dateEnd',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '9%',
            'format' => ['date', 'php:d.m.Y'],
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Дата окончание аренды'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
                'widgetClass' =>  'kartik\datecontrol\DateControl',
                'options' => [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                ],
//                'pjaxContainerId' => 'pjax_movement_grid',
                'formOptions' => [ 'action' => Url::toRoute(['order/update-ajax']) ],
            ],
        ],
        [
            'header'=>'Статус',
//            'value' => '12'
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'contentOptions' => ['class' => 'action-column'],
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['order-product/delete-ajax','id'=>$model->id]), [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-pjax' => '#pjax_movement_grid',
                        'data-confirm'=>'Вы действительно хотите удалить позицию из заказа?',
                        'data-method'=>'post'
                    ]);
                },
            ],
        ],
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
        ],
    ],
    'showPageSummary' => true,
]); ?>
    <div class="pull-right">
        <?=$selectOperations?>
    </div>
    <?php Pjax::end(); ?>
<?php
$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-home"></i> Общее',
        'content'=>$this->render('_tabMain', [
            'model'=>$model,
//            'form'=>$form,
        ]),
        'active'=>true
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Оплата',
        'content'=>$this->render('_tabPayment', [
            'model'=>$model,
//            'form'=>$form,
        ]),
//            'linkOptions'=>[
////                                'data-url'=>Url::to(['/file/index','hash'=>new JsExpression("function (){return 'hi'}")])
//                'data-url'=>Url::to(['/file/index'])
//            ],
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Движения товаров',
        'content'=>$this->render('_tabWarehouse', [
            'model'=>$model,
            'dataProviderMovement'=>$dataProviderMovement,
//            'form'=>$form,
        ]),
//            'linkOptions'=>[
////                                'data-url'=>Url::to(['/file/index','hash'=>new JsExpression("function (){return 'hi'}")])
//                'data-url'=>Url::to(['/file/index'])
//            ],
    ],
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> Профиль клиента',
        'linkOptions'=>[
//                                'data-url'=>Url::to(['/file/index','hash'=>new JsExpression("function (){return 'hi'}")])
//            'data-url'=>Url::to(['/user/profile','id'=>$model->client_id])
        ],
    ],


];
?>
<!--<div class="row">-->
    <br><br>
    <?=TabsX::widget([
        'items'=>$items,
        'position'=>TabsX::POS_ABOVE,
        'encodeLabels'=>false
    ]);
    ?>
<!--</div>-->

</div>

<?php
    Modal::begin([
        'header' => '<h4 id="modalTitle"><h4></h4>',
        'id' => 'order-confirm-modal',
        'size' => 'modal-md',
        'clientOptions' => ['backdrop' => 'static'],
        'footer' => 'Кнопка',
    ]);
?>
<?php
    Pjax::begin(['id' => 'pjax_order-content-confirm-modal']);
    Pjax::end();
?>
<?php
    Modal::end();
?>
<?php
$urlContentConfirmModal=Url::toRoute("order/content-confirm-modal-ajax");
$content = Pjax::begin(['id' => 'pjax_order-content-confirm-modal']);
//$contentEnd = Pjax::end();
//$content = $content . $contentEnd;
$js = <<<JS
    function changeOperation(e) {
        var keys = $('#order-grid').yiiGridView('getSelectedRows').length;
        if (keys==0) {
            alert('Не выделено ни одного элемента');
            return false;
        }
        $.post({
           url: "$urlContentConfirmModal", // your controller action
           dataType: 'json',
           data: {
                    keylist: $('#order-grid').yiiGridView('getSelectedRows'),
                    operation: e.params.args.data.id
               },
           success: function(response) {
               // console.log(response);
               if (response.status === 'success') {
                    $("#modalBlock").html(response.data)
                    $('#modal').removeClass('fade');
                    $('#modal').modal('show'); 
               }
           },
        });
    }
JS;
$this->registerJs($js);
?>