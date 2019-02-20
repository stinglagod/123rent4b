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
?>
<?= GridView::widget([
    'id' => 'pjax_order-product_grid',
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
            'header' => '#',
        ],
        [
            'attribute' => 'type',
            'group'=>true,
            'value' => function(\common\models\OrderProduct $data) {
                if ($data->type==\common\models\OrderProduct::RENT) {
                    return 'Аренда';
                } elseif($data->type==\common\models\OrderProduct::SALE) {
                    return 'Продажа';
                } elseif($data->type==\common\models\OrderProduct::SERVICE) {
                    return 'Услуга';
                } else {
                    return $data->type;
                }
            }
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
            'pageSummary' => false,
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Цена'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 999999,'step' => 100]
                ],
                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
//              Сдела так потмоу что, при использования refreshGrid после удалении позиции при обновлении таблицы используется другой url
                'pluginEvents' => [
                    'editableSuccess' => 'function(event, val, form, data) { $.pjax.reload({container:"#pjax_order-product_grid"}); }',
                ],
            ],
//            'refreshGrid' => true
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
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ],
                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
//              Сдела так потмоу что, при использования refreshGrid после удалении позиции при обновлении таблицы используется другой url
                'pluginEvents' => [
                    'editableSuccess' => 'function(event, val, form, data) { $.pjax.reload({container:"#pjax_order-product_grid"}); }',
                ],
            ],
//            'refreshGrid' => true
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'period',
            'format' => ['decimal', 0],
            'pageSummary' => false,
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Период'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                'options' => [
                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                ],
                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
//              Сдела так потмоу что, при использования refreshGrid после удалении позиции при обновлении таблицы используется другой url
                'pluginEvents' => [
                    'editableSuccess' => 'function(event, val, form, data) { $.pjax.reload({container:"#pjax_order-product_grid"}); }',
                ],
            ],
//            'refreshGrid' => true
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
            'class' => 'kartik\grid\FormulaColumn',
            'header' => 'Сумма',
            'vAlign' => 'middle',
            'value' => function (\common\models\OrderProduct $model, $key, $index, $widget) {
                $summ=$model->cost*$model->qty;
                if ($model->type==\common\models\OrderProduct::RENT) {
                    $summ*=$model->period;
                }
                return $summ;
//                $p = compact('model', 'key', 'index');
//                return $widget->col(3, $p) * $widget->col(4, $p) * $widget->col(5, $p);
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
                        'data-pjax' => '#pjax_order-product_grid',
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
<?//= GridView::widget([
////    'id' => 'pjax_order-product_grid',
//    'dataProvider' => $dataProvider,
//    'layout' => "{items}\n{summary}\n{pager}",
//    'pjax' => true,
//    'columns' => [
//        [
//            'class' => 'kartik\grid\SerialColumn',
//            'header' => '',
//        ],
//        [
//            'attribute' => 'type',
//            'group'=>true,
//            'value' => function(\common\models\OrderProduct $data) {
//                if ($data->type==\common\models\OrderProduct::RENT) {
//                    return 'Аренда';
//                } elseif($data->type==\common\models\OrderProduct::SALE) {
//                    return 'Продажа';
//                } elseif($data->type==\common\models\OrderProduct::SERVICE) {
//                    return 'Услуга';
//                } else {
//                    return $data->type;
//                }
//            }
//        ],
//        [
//            'attribute' => 'product_id',
//            'pageSummary' => 'Итого',
//            'headerOptions' => ['class' => 'text-center'],
//            'width' => '9%',
//            'vAlign' => 'middle',
//            'value' => function (\common\models\OrderProduct $data) {
//                return $data->product->name;
//            },
//            'format' => 'raw',
//        ],
//        [
//            'class' => 'kartik\grid\EditableColumn',
//            'attribute' => 'cost',
//            'format' => ['decimal', 2],
//            'hAlign' => 'right',
//            'vAlign' => 'middle',
//            'headerOptions' => ['class' => 'kv-sticky-column'],
//            'contentOptions' => ['class' => 'kv-sticky-column'],
//            'editableOptions' => [
//                'header' => Yii::t('app', 'Цена'),
//                'size' => 'md',
//                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
//                'options' => [
//                    'pluginOptions' => ['min' => 0, 'step' => 100, 'decimals' => 2]
//                ],
////                'pjaxContainerId' => 'pjax_order-product_grid',
//                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
//            ],
//        ],
////        [
////            'class' => 'kartik\grid\EditableColumn',
////            'attribute' => 'qty',
////            'format' => ['decimal', 0],
////            'pageSummary' => true,
////            'hAlign' => 'right',
////            'vAlign' => 'middle',
////            'headerOptions' => ['class' => 'kv-sticky-column'],
////            'contentOptions' => ['class' => 'kv-sticky-column'],
////            'editableOptions' => [
////                'header' => Yii::t('app', 'Количество'),
////                'size' => 'md',
////                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
////                'options' => [
//////                        TODO: Добавить максимум по кол-ву свободного товара на даты аренды
////                    'pluginOptions' => ['min' => 0, 'max' => 5000]
////                ],
////                'pjaxContainerId' => 'pjax_order-product_grid',
////                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
////                //               TODO: сделал так. Перезагружает всю сттаницу. проблема на странице https://github.com/kartik-v/yii2-editable/issues/168
////                'pluginEvents' => [
////                    'editableSuccess' => 'function(event, val, form, data) { $.pjax.reload({container:"#pjax_order-product_grid"}); }',
////                ],
////            ],
//////            'refreshGrid' => true
////        ],
//        [
//            'class' => 'kartik\grid\EditableColumn',
//            'attribute' => 'period',
//            'format' => ['decimal', 0],
//            'pageSummary' => true,
//            'hAlign' => 'right',
//            'vAlign' => 'middle',
//            'headerOptions' => ['class' => 'kv-sticky-column'],
//            'contentOptions' => ['class' => 'kv-sticky-column'],
//            'editableOptions' => [
//                'header' => Yii::t('app', 'Период сдачи'),
//                'size' => 'md',
//                'inputType' => \kartik\editable\Editable::INPUT_SPIN,
//                'options' => [
////                        TODO: Добавить максимум по кол-ву свободного товара на даты аренды
//                    'pluginOptions' => ['min' => 0, 'max' => 5000]
//                ],
////                'pjaxContainerId' => 'pjax_order-product_grid',
//                'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],
//            ],
//            'refreshGrid'=>true,
//
//        ],
//
////        [
////            'class' => 'kartik\grid\FormulaColumn',
////            'header' => 'Сумма',
////            'vAlign' => 'middle',
////            'value' => function (\common\models\OrderProduct $model, $key, $index, $widget) {
////                $summ=$model->cost*$model->qty;
////                if ($model->type==\common\models\OrderProduct::RENT) {
////                    $summ*=$model->period;
////                }
////                return $summ;
//////                $p = compact('model', 'key', 'index');
//////                return $widget->col(3, $p) * $widget->col(4, $p) * $widget->col(5, $p);
////            },
////            'headerOptions' => ['class' => 'kartik-sheet-style'],
////            'hAlign' => 'right',
//////            'width' => '20%',
////            'format' => ['decimal', 2],
////            'mergeHeader' => true,
////            'pageSummary' => true,
////            'footer' => true
////        ],
//        [
//            'class' => 'kartik\grid\EditableColumn',
//            'attribute' => 'dateBegin',
//            'hAlign' => 'center',
//            'vAlign' => 'middle',
//            'width' => '9%',
//            'format' => ['date', 'php:d.m.Y'],
//            'headerOptions' => ['class' => 'kv-sticky-column'],
//            'contentOptions' => ['class' => 'kv-sticky-column'],
//            'editableOptions' => [
//                'header' => Yii::t('app', 'Дата начала аренды'),
//                'size' => 'md',
//                'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
//                'widgetClass' =>  'kartik\datecontrol\DateControl',
//                'options' => [
//                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
//                ],
////                'pjaxContainerId' => 'pjax_movement_grid',
//                'formOptions' => [ 'action' => Url::toRoute(['order/update-ajax']) ],
//            ],
//        ],
//        [
//            'class' => 'kartik\grid\EditableColumn',
//            'attribute' => 'dateEnd',
//            'hAlign' => 'center',
//            'vAlign' => 'middle',
//            'width' => '9%',
//            'format' => ['date', 'php:d.m.Y'],
//            'headerOptions' => ['class' => 'kv-sticky-column'],
//            'contentOptions' => ['class' => 'kv-sticky-column'],
//            'editableOptions' => [
//                'header' => Yii::t('app', 'Дата окончание аренды'),
//                'size' => 'md',
//                'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
//                'widgetClass' =>  'kartik\datecontrol\DateControl',
//                'options' => [
//                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
//                ],
////                'pjaxContainerId' => 'pjax_movement_grid',
//                'formOptions' => [ 'action' => Url::toRoute(['order/update-ajax']) ],
//            ],
//        ],
////        [
////            'header'=>'Статус',
//////            'value' => '12'
////        ],
////        [
////            'class' => 'kartik\grid\ActionColumn',
////            'template' => '{delete}',
////            'contentOptions' => ['class' => 'action-column'],
////            'buttons' => [
////                'delete' => function ($url, $model, $key) {
////                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['order-product/delete-ajax','id'=>$model->id]), [
////                        'title' => Yii::t('yii', 'Delete'),
////                        'data-pjax' => '#pjax_movement_grid',
////                        'data-confirm'=>'Вы действительно хотите удалить позицию из заказа?',
////                        'data-method'=>'post'
////                    ]);
////                },
////            ],
////        ],
////        [
////            'class' => 'kartik\grid\CheckboxColumn',
////            'headerOptions' => ['class' => 'kartik-sheet-style'],
////        ],
//    ],
//    'showPageSummary' => true,
//]); ?>


