<?php

use rent\entities\Shop\Order\Payment;
use rent\helpers\PaymentHelper;
use rent\helpers\TextHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\date\DatePicker;
use rent\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use rent\entities\Shop\Order\Order;
use rent\helpers\OrderHelper;
use rent\entities\Shop\Order\Status;

/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\Shop\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $balances array */

$this->title = 'Касса';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group pull-right" role="group" aria-label="toolbar">
                    <button type="button" class="btn btn-warning" id="payments-export-to-excel" data-url='<?=Url::toRoute(["shop/cashbox/export"]);?>' title="Выгрузить в Excel">
                        <span class="fa fa-file-excel-o" aria-hidden="true"> Выгрузить в Excel
                    </button>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <div class="btn-group" role="group" aria-label="toolbar">
                    <?= Html::a('Добавить поступление Д/С', ['create-plus'], ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Добавить вывод Д/С', ['create-minus'], ['class' => 'btn btn-warning']) ?>
                    <?= Html::a('Корректировка', ['create-correct'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <br>
        <?

        if (Yii::$app->user->can('admin')) {
            echo $this->render('_header-administrator', [
                'balances' => $balances,
            ]);
        } else {
            echo $this->render('_header-default', [
                'balances' => $balances,
            ]);
        }

        ?>
    </div>
    <div class="box-body table-responsive">
          <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{items}\n{summary}\n{pager}",
                'id' => 'cashbox-index-grid',
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'showPageSummary' => true,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'width' => '5%',
                        'hAlign' => 'center',
                        'vAlign' => 'left',
                    ],
                    [
                        'attribute' => 'dateTime',
                        'format' => ['date', 'php:D, d F Y'],
                        'hAlign' => 'center',
                        'vAlign' => 'middle',
                        'width' => '25%',
                        'headerOptions' => ['class' => 'kv-sticky-column'],
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd',
                            ],
                        ]),
                    ],
                    [
                        'attribute' => 'order_id',
                        'vAlign' => 'middle',
                        'value' => function (Payment $payment) {
                            if ($payment->order) {
                                return Html::a(Html::encode($payment->order->name), Url::to(['shop/order/update', 'id' => $payment->order->id]),['data-pjax'=>0,'target'=>"_blank"]);
                            } else {
                                null;
                            }

                        },
                        'format' => 'raw',
                    ],
                    [
                        'label'=>'',
                        'attribute' => 'type_id',
                        'value' => function ( Payment $model) {
                            return PaymentHelper::getTypeIconHtml($model->type_id);
                        },
                        'format' => 'raw',
                        'hAlign' => 'center',
                        'vAlign' => 'left',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => PaymentHelper::paymentTypeList(),
                        'filterWidgetOptions' => [
                            'hideSearch' => true,
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Тип Платежа', 'multiple' => false],
                    ],
                    [
                        'attribute' => 'responsible_id',
                        'hAlign' => 'left',
                        'vAlign' => 'middle',
                        'width' => '15%',
                        'value' => function (Payment $data) {
                            if ($data->responsible_id) {
//                            return $data->responsible->getShortName();
                                $url='';
                                if ($user=$data->responsible) {
                                    $url=$user->getAvatarUrl();
                                }

                                return '<img src="'.$url.'" class="img-circle" style="width: 30px;" alt="User Image">'.'&nbsp'.$data->responsible->getShortName(); /*archi*/
                            } else {
                                return $data->responsible_name;
                            }

                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => User::getUserArray(),
                        'filterWidgetOptions' => [
                            'hideSearch' => true,
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Менеджер', 'multiple' => false],
                        'format' => 'raw',
                    ],
                    'payer_name',
                    [
                        'attribute' => 'sum',
                        'hAlign' => 'right',
                        'format' => ['decimal', 2],
                        'vAlign' => 'middle',
                        'width' => '15%',
                        'value' => function (Payment $data) {
                            return PaymentHelper::getSum($data);
                        },
                        'pageSummary' => true
                    ],
                    'note',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'visibleButtons' => [
                            'update' => function (Payment $model, $key, $index) {
                                return $model->canUpdate() ? true : false;
                            },
                            'delete' => function (Payment $model, $key, $index) {
                                return $model->canDelete() ? true : false;
                            },
                        ],
                        'template' => '{update} {delete}',
                    ],
                ],
          ]); ?>
    </div>
</div>

<?php
$_csrf=Yii::$app->request->getCsrfToken();
$js = <<<JS
    //Выгрузка отображенных заказов
    $("body").on("click", '#payments-export-to-excel', function() {
        // alert('Выгружаем заказ');
        let url=this.dataset.url+'?'+window.location.search.replace( '?', '');
        $.post({
           url: url,
           type: "POST",
           data: {
                 _csrf : "$_csrf"
           },
           success: function(response) {
               if (response.status === 'success') {
                   document.location.href=response.data;
               }
           },
        });
    })
     
JS;
$this->registerJs($js);

?>