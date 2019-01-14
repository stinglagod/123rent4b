<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\grid\GridView;
use kartik\tabs\TabsX;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-index box box-primary">
    <?php Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{summary}\n{pager}",
    'pjax' => true,
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'header' => '',
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
            'attribute' => 'qty',
            'pageSummary' => true,
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Количество'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                'pjaxContainerId' => 'pjax_movement_grid',
                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
            ],
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'cost',
            'pageSummary' => true,
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Цена'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                'pjaxContainerId' => 'pjax_movement_grid',
                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
            ],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'contentOptions' => ['class' => 'action-column'],
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-pjax' => '#pjax_movement_grid',
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
        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Склад',
        'content'=>$this->render('_tabWarehouse', [
            'model'=>$model,
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


