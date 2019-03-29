<?php
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\editable\Editable;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
    'id' => 'order-cash-grid',
    'pjax' => true,
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'header' => '',
        ],
        [
            'attribute' => 'dateTime',
            'group' => true,  // enable grouping
            'value' => function (\common\models\Cash $data) {
                return $data->dateTime."<br><small>".$data->autor->getShortName()."</small>";
            },
            'format' => 'raw',
            'pageSummary' => 'Итого',
        ],
        [
            'attribute' => 'cashType_id',
            'value' => function (\common\models\Cash $data) {
                if ($data->cashType_id) {
                    return $data->cashType->name;
                } else {
                    return '';
                }

            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'sum',
            'value' => function (\common\models\Cash $data) {
                return $data->sum."<br><small>".$data->payer."</small>";
            },
            'format' => 'raw',
            'pageSummary' => true,
            'footer' => true
        ],
        [
            'attribute' => 'note',
//            'value' => function (\common\models\Cash $data) {
//                return $data->note;
//            },
            'format' => 'raw',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'contentOptions' => ['class' => 'action-column'],
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute([    'cash/delete','id'=>$model->id]), [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-pjax' => '#order-cash-grid',
                        'data-confirm'=>'Вы действительно хотите удалить платеж?',
                        'data-method'=>'post'
                    ]);
                },
            ],
        ],
    ],
    'showPageSummary' => true,
]); ?>