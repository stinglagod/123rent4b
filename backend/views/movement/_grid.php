<?php
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\editable\Editable;
use yii\helpers\ArrayHelper;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 19.12.2018
 * Time: 10:46
 */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'name',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
//            'readonly' => function($model, $key, $index, $widget) {
//                return ($model->readOnly()); // do not allow editing of inactive records
//            },
            'editableOptions' => [
                'header' => Yii::t('app', 'Имя'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                'pjaxContainerId' => 'pjax_movement_grid',
            ],
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'dateTime',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'width' => '9%',
            'format' => ['date', 'php:d.m.Y'],
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'CPD-DATE_TAKE_DOC'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
                'widgetClass' =>  'kartik\datecontrol\DateControl',
                'options' => [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
                ],
                'pjaxContainerId' => 'pjax_movement_grid',
            ],
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'qty',
            'hAlign' => 'center',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => [
                'header' => Yii::t('app', 'Имя'),
                'size' => 'md',
                'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                'pjaxContainerId' => 'pjax_movement_grid',
            ],
        ],
        [
            'attribute' => 'action_id',
            'headerOptions' => ['class' => 'text-center'],
            'width' => '9%',
            'vAlign' => 'middle',
            'value' => function (\common\models\Movement $data) {
                return $data->action->name;
            },
            'format' => 'raw',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
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
    ],
]); ?>

<?php
Editable::widget(
    [
        'name' => 'hidden',
        'pjaxContainerId' => 'pjax-container',
    ]
);
?>
