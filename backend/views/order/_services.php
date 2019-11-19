<?php
use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<hr>
<h2>Услуги</h2>

<?=GridView::widget([
    'id' => "pjax_orderservice_grid",
    'options' => [
        'class'=>'grid-view grid-orderservice'
    ],
    'pjax' => true,
    'pjaxSettings'=>[
        'options'=>[
            'enablePushState' => false,
            'timeout' => 5000
        ],
     ],
    'dataProvider' => $dataProviderService,
//            'filterModel' => $searchModel,
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'header' => '',
        ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'name',
            'header'=>'Наимменование',
            'pageSummary' => 'Итого',
            'headerOptions' => ['class' => 'text-center'],
            'width' => '9%',
            'vAlign' => 'middle',
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
            'header'=>'Стоимость',
            'format' => ['decimal', 2],
            'pageSummary' => true,
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'headerOptions' => ['class' => 'kv-sticky-column'],
            'contentOptions' => ['class' => 'kv-sticky-column'],
            'editableOptions' => function ($data, $key, $index){
                return [
                    'name'=>'cost',
                    'value' => $data['cost'],
                    'header' => Yii::t('app', 'Цена'),
                    'size' => 'md',
                    'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax','id'=>$data['id']]) ],
                    'pluginEvents' => [
                        "editableSuccess"=>'gridOrderService.onEditableGridSuccess',
                        "editableSubmit"=> 'gridOrderService.onEditableGridSubmit',
                    ]
                ];
            },
            'refreshGrid'=>false,
            'readonly' => function($data, $key, $index, $widget) use ($orderProduct) {
//              TODO: лишний запрос. Оптимизировать надо
                if (empty($orderProduct)) {
                    $orderProduct=\common\models\OrderProduct::findOne($data['id']);
                }
                return ($orderProduct->readOnly()); // do not allow editing of inactive records
            },
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'contentOptions' => ['class' => 'action-column'],
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['order-product/delete-ajax','id'=>$model['id']]), [
                        'title' => Yii::t('yii', 'Delete'),
//                        'data-pjax' => '#pjax_order-product_grid_'.$model['id'],
                        'data-pjax' => "#pjax_orderservice_grid",
                        'data-confirm'=>'Вы действительно хотите удалить позицию из заказа?',
                        'data-method'=>'post'
                    ]);
                },
            ],

        ],

    ]
    ]);
?>
<?php

$js = <<<JS
// Найден небольшой глюк с Editable. событие editableSuccess возникает после перезагрузки gridа pjax.
// Поэтому при обновлении событие срабатывается и все pjax обновляются.
// Сделал проверку на первый запуск
    var first=0;
    var gridOrderService = {
        onEditableGridSuccess: function (event, val, form, data) {
            if (first) {
                // console.log('+++');
                first=0;
                reloadPjaxs('#pjax_orderservice_grid','#sum-order-pjax','#pjax_alerts',);
            }
        },
        onEditableGridSubmit: function (val, form) {
            first=1;
        }
    }
JS;
$this->registerJs($js,yii\web\View::POS_BEGIN);
?>
