<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\date\DatePicker;
use rent\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use rent\entities\Shop\Order\Order;

/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\Shop\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Все заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index box box-primary">
    <div class="box-header with-border">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group pull-right" role="group" aria-label="toolbar">
                    <button type="button" class="btn btn-warning" id="orders-export-to-excel" data-url='<?=Url::toRoute(["order/export"]);?>' title="Выгрузить в Excel">
                        <span class="fa fa-file-excel-o" aria-hidden="true"> Выгрузить заказы
                    </button>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <div class="btn-group" role="group" aria-label="toolbar">
                    <?= Html::a(Yii::t('app', 'Новый заказ'), '#', ['class' => 'btn btn-success btn-flat createNewOrder']) ?>
                    <?= Html::a('Новый заказ', ['create'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
<!--            --><?php //$form = ActiveForm::begin([
//                'action' => ['index'],
//                'method' => 'get',
//                'options' => [
//                    'data-pjax' => 1,
//                    'class' =>"form-inline"
//                ],
//            ]); ?>
<!--            <div class="col-md-7">-->
<!---->
<!--                <div class="form-group" style="padding-right: 20px;">-->
<!--                    --><?//= $form->field($searchModel, 'owner')->checkbox(['class'=>'filterField']) ?>
<!--                </div>-->
<!--                <div class="form-group" style="padding-right: 20px;">-->
<!--                    --><?//= $form->field($searchModel, 'hideClose')->checkbox(['class'=>'filterField']) ?>
<!--                </div>-->
<!--                <div class="form-group"style="padding-right: 20px;">-->
<!--                    --><?//= $form->field($searchModel, 'hidePaid')->checkbox(['class'=>'filterField']) ?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-2">-->
<!--                <div class="form-group pull-right">-->
<!--                    --><?//= Html::submitButton(Yii::t('app', 'Поиск'), ['class' => 'btn btn-primary']) ?>
<!--                </div>-->
<!--            </div>-->
<!--            --><?php //ActiveForm::end(); ?>

        </div>

    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'id' => 'order-index-grid',
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'pjax' => true,
            'columns' => [
//                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'id',
                    'width' => '5%',
                    'hAlign' => 'center',
                    'vAlign' => 'left',
                ],
                [
                    'attribute' => 'date_begin',
                    'format' => 'datetime',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '10%',
                    'headerOptions' => ['class' => 'kv-sticky-column'],
//                    'filter' => DatePicker::widget([
//                        'model' => $searchModel,
//                        'attribute' => 'date_begin',
//                        'type' => DatePicker::TYPE_INPUT,
//                        'pjaxContainerId'=> 'order-index-grid-pjax',
//                        'separator' => '.',
//                        'pluginOptions' => [
//                            'format' => 'yyyy-mm-dd',
//                            'todayHighlight' => true,
//                            'todayBtn' => true,
//                            'autoclose' => true,
//                        ],
//                    ]),
                    'contentOptions' => function ( Order $model) {

                        $date=strtotime(date("Y-m-d 00:00:00"));
                        $currentNumWeek=(int)date("W",$date);
                        $numWeek=(int)date("W",$model->date_begin);

                        if ($model->date_begin >= $date) {
                            if ($numWeek == $currentNumWeek) {
                                return ['style' => 'background-color:#ea9999'];
                            } else if ($numWeek == ($currentNumWeek+1)) {
                                return ['style' => 'background-color:#ffe599'];
                            } else if ($numWeek == ($currentNumWeek+2)) {
                                return ['style' => 'background-color:#b6d7a8'];
                            }
                        }else {
                            return ['style' => 'background-color:#b7b7b7'];
                        }
                    },
                ],
                [
                    'attribute' => 'name',
                    'vAlign' => 'middle',
                    'value' => function (Order $data) {
                        return Html::a(Html::encode($data->name).'<br><small>'.$data->customerData->name.'</small>', Url::to(['update', 'id' => $data->id]),['data-pjax'=>0,'target'=>"_blank"]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'responsible_id',
                    'hAlign' => 'left',
                    'vAlign' => 'middle',
                    'width' => '15%',
                    'value' => function (Order $data) {
                        if ($data->responsible_id) {
                            return $data->responsible->getShortName();
//                            return '<img src="'.$data->responsible->avatarUrl.'" class="img-circle" style="width: 30px;" alt="User Image">'.'&nbsp'.$data->getResponsibleName(); /*archi*/
                        } else {
                            return $data->responsible_name;
                        }

//                        return $data->getResponsibleName();
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
//                [
//                    'attribute' => 'status_id',
//                    'hAlign' => 'center',
//                    'vAlign' => 'middle',
//                    'value' => function (Order $data) {
//                        if ($data->status_id) {
//                            return $data->status->shortName;
//                        }
//                    },
//                    'filterType' => GridView::FILTER_SELECT2,
//                    'filter' => $searchModel->statusList(),
//                    'filterWidgetOptions' => [
//                        'pluginOptions' => ['allowClear' => true],
//                    ],
//                    'filterInputOptions' => ['placeholder' => 'Cтатус', 'multiple' => false],
//                    'format' => 'raw',
//                ],
//                [
//                    'attribute' => 'statusPaid_id',
//                    'hAlign' => 'center',
//                    'vAlign' => 'middle',
//                    'value' => function (Order $data) {
//                        if ($data->statusPaid_id) {
//                            return $data->getStatusPaidName();
//                        }
//                    },
//                    'contentOptions' => function (\common\models\Order $model, $key, $index, $column) {
//                        $paidStatus=$model->getPaidStatus();
//                        if ($paidStatus == \common\models\Order::NOPAID) {
//                            return ['style' => 'background-color:#ea9999'];
//                        } else if ($paidStatus == \common\models\Order::FULLPAID) {
//                            return ['style' => 'background-color:#b6d7a8'];
//                        } else if ($paidStatus == \common\models\Order::PARTPAID) {
//                            return ['style' => 'background-color:#ffe599'];
//                        } else if ($paidStatus == \common\models\Order::OVAERPAID) {
//                            return ['style' => 'background-color:#ea9999'];
//                        }
//                    },
//                    'filterType' => GridView::FILTER_SELECT2,
//                    'filter' => Order::getStatusPaidsArray(),
//                    'filterWidgetOptions' => [
//                        'pluginOptions' => ['allowClear' => true],
//                    ],
//                    'filterInputOptions' => ['placeholder' => 'Cтатус', 'multiple' => false],
//                    'format' => 'raw',
//                ],
                'description',                

                ['class' => 'yii\grid\ActionColumn'],
            ],

        ]); ?>
    </div>
</div>
<?php
$_csrf=Yii::$app->request->getCsrfToken();
$js = <<<JS
    //Выгрузка отображенных заказов
    $("body").on("click", '#orders-export-to-excel', function() {
        // alert('Выгружаем заказ');
        var url=this.dataset.url;
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
