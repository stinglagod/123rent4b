<?php
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\editable\Editable;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 18.02.2019
 * Time: 14:39
 */
/* @var $orderBlock_id integer */
?>
<?php
 $grid_id='pjax_order-product_grid_'.$orderBlock_id;
// echo $grid_id; exit;
?>
<?= GridView::widget([
    'id' => $grid_id,
    'options' => [
        'class'=>'grid-view grid-orderproduct'
    ],
    'pjax' => true,
    'pjaxSettings'=>[
        'options'=>[
            'enablePushState' => false
        ],
     ],
    'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'header' => '',
        ],
//        [
//            'attribute' => 'type',
//            'header'=>'Операция',
//            'group'=>true,
//            'value' => function($data) {
//                if ($data['type']==\common\models\OrderProduct::RENT) {
//                    return 'Аренда';
//                } elseif($data['type']==\common\models\OrderProduct::SALE) {
//                    return 'Продажа';
//                } elseif($data['type']==\common\models\OrderProduct::SERVICE) {
//                    return 'Услуга';
//                } else {
//                    return $data['type'];
//                }
//            }
//        ],
        [
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                if ($model['name']) {
                    return GridView::ROW_COLLAPSED;
                } else {
                    return '';
                }
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('../order/_expand-row-details', [
                    'parent_id' => $model['id'],
                    'orderBlock_id' => $model['orderBlock_id']
                ]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'name',
            'header'=>'Продукт',
            'pageSummary' => 'Итого',
            'headerOptions' => ['class' => 'text-center'],
            'width' => '9%',
            'vAlign' => 'middle',
            'readonly'=>function ($data) {
                if ($data['type']==\common\models\OrderProduct::COLLECT) {
                    return false;
                } else {
                    return true;
                }
            },
            'value' => function ($data) {
                if ($data['type']==\common\models\OrderProduct::COLLECT){
                    return $data['name'];
                } else {
                    $model=\common\models\Product::findOne($data['product_id']);
                    return $model->name;
                }
            },
            'format' => 'raw',
            'editableOptions' => function ($data, $key, $index) {
                return [
                    'name'=>'name',
                    'value' => $data['name'],
                    'header' => Yii::t('app', 'Наименование'),
                    'size' => 'md',

                    'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$data['id']]) ],
                ];
            },
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'cost',
            'header'=>'Цена',
            'format' => ['decimal', 2],
            'pageSummary' => false,
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => function ($data, $key, $index) {
                return [
                    'name'=>'cost',
                    'value' => $data['cost'],
                    'header' => Yii::t('app', 'Цена'),
                    'size' => 'md',
                    'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                    'options' => [
                        'pluginOptions' => [
                            'min' => 0,
                            'max' => 999999,
                            'step' => 100,
                            'decimals' => 2,
                            'postfix' => 'руб.',
                        ]
                    ],
                    'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$data['id']]) ],
                ];
            },
            'refreshGrid'=>true,
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'qty',
            'header'=>'Кол-во',
            'format' => ['decimal', 0],
            'pageSummary' => true,
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => function ($data, $key, $index) {
                return [
                    'header' => Yii::t('app', 'Количество'),
                    'name'=>'qty',
                    'value' => $data['qty'],
                    'size' => 'md',
                    'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                    'options' => [
                        'pluginOptions' => ['min' => 0, 'max' => 5000]
                    ],
                    'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$data['id']]) ],
                ];
            },
            'refreshGrid'=>true,
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'period',
            'header'=>'Период',
            'format' => ['decimal', 0],
            'pageSummary' => false,
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => function ($model, $key, $index) {
                return [
                    'header' => Yii::t('app', 'Период'),
                    'size' => 'md',
                    'name'=>'period',
                    'value' => $model['period'],
                    'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                    'options' => [
                        'pluginOptions' => ['min' => 0, 'max' => 5000]
                    ],
                    'formOptions' => ['action' => Url::toRoute(['order-product/update-ajax', 'id' => $model['id']])],
                ];
             },
            'refreshGrid'=>true,
        ],
//        [
//            'class' => 'kartik\grid\EditableColumn',
//            'attribute' => 'dateBegin',
//            'header'=>'Начало',
//            'hAlign' => 'center',
//            'vAlign' => 'middle',
//            'width' => '9%',
//            'format' => ['date', 'php:d.m.Y'],
//            'headerOptions' => ['class' => 'kv-sticky-column'],
//            'contentOptions' => ['class' => 'kv-sticky-column'],
//            'editableOptions' => function ($model, $key, $index) {
//                return [
//                    'header' => Yii::t('app', 'Дата начала аренды'),
//                    'name'=> 'dateBegin',
//                    'value' => $model['dateBegin'],
//                    'size' => 'md',
//                    'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
//                    'widgetClass' =>  'kartik\datecontrol\DateControl',
//                    'options' => [
//                        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
//                    ],
//                    'formOptions' => ['action' => Url::toRoute(['order-product/update-ajax', 'id' => $model['id']])],
//                ];
//            },
//        ],
//        [
//            'class' => 'kartik\grid\EditableColumn',
//            'attribute' => 'dateEnd',
//            'header'=> 'Окончание',
//            'hAlign' => 'center',
//            'vAlign' => 'middle',
//            'width' => '9%',
//            'format' => ['date', 'php:d.m.Y'],
//            'headerOptions' => ['class' => 'kv-sticky-column'],
//            'contentOptions' => ['class' => 'kv-sticky-column'],
//            'editableOptions' => function ($model, $key, $index) {
//                return [
//                    'header' => Yii::t('app', 'Дата окончание аренды'),
//                    'name'=> 'dateEnd',
//                    'value' => $model['dateEnd'],
//                    'size' => 'md',
//                    'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
//                    'widgetClass' => 'kartik\datecontrol\DateControl',
//                    'options' => [
//                        'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
//                    ],
//                    'formOptions' => ['action' => Url::toRoute(['order-product/update-ajax', 'id' => $model['id']])],
//                ];
//            },
//        ],
        [
            'class' => 'kartik\grid\FormulaColumn',
            'header' => 'Сумма',
            'vAlign' => 'middle',
            'value' => function ($model) {
                $summ=$model['cost']*$model['qty'];
                if ($model['type']==\common\models\OrderProduct::RENT) {
                    $summ*=$model['period'];
                }
                return $summ;
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'hAlign' => 'right',
            'format' => ['decimal', 2],
            'mergeHeader' => true,
            'pageSummary' => true,
            'footer' => true
        ],
        [
            'header'=>'Статус',
            'value' => function ($model) {
                if ($status=\common\models\OrderProduct::findOne($model['id'])->getStatus()) {
                    return $status['text'];
                } else {
                    return '';
                }
            }
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'contentOptions' => ['class' => 'action-column'],
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['order-product/delete-ajax','id'=>$model['id']]), [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-pjax' => '#pjax_order-product_grid_'.$model['id'],
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



