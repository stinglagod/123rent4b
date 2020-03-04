<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\date\DatePicker;
use \common\models\User;
use yii\helpers\ArrayHelper;
use common\models\Status;
use common\models\Order;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Все заказы');
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
                    'attribute' => 'dateBegin',
                    'format' => ['date', 'php:D, d F Y'],
//                    'value' => function ($data) {
//                        setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');
////                        setlocale(LC_ALL, 'ru_RU');
////                        return setlocale(LC_ALL, 0);
//                        return strftime("%B %d, %Y", time());
//                        return $data['dateBegin'];
//                        return 1;
//                    },
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '10%',
                    'headerOptions' => ['class' => 'kv-sticky-column'],
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'dateBegin',
                        'type' => DatePicker::TYPE_INPUT,
                        'pjaxContainerId'=> 'order-index-grid-pjax',
                        'separator' => '.',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'todayBtn' => true,
                            'autoclose' => true,
                        ],
                    ]),
                    'contentOptions' => function (Order $model, $key, $index, $column) {
                        $dateBegin=strtotime($model->dateBegin);
                        $date=strtotime("now");
                        $currentNumWeek=(int)date("W",$date);
                        $numWeek=(int)date("W",$dateBegin);
//                        $date1=strtotime("+7 day");
//                        $date2=strtotime("+14 day");
//                        $date3=strtotime("+21 day");

                        if ($dateBegin >= $date) {
                            if ($numWeek == $currentNumWeek) {
                                return ['style' => 'background-color:#ea9999'];
                            } else if ($numWeek == ($currentNumWeek+1)) {
                                return ['style' => 'background-color:#ffe599'];
                            } else if ($numWeek == ($currentNumWeek+2)) {
                                return ['style' => 'background-color:#b6d7a8'];
                            }
                        }
                    },
                ],
                [
                    'attribute' => 'name',
                    'vAlign' => 'middle',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->name).'<br><small>'.$data->customer.'</small>', Url::to(['update', 'id' => $data->id]),['data-pjax'=>0,'target'=>"_blank"]);
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
                            return '<img src="'.$data->responsible->avatarUrl.'" class="img-circle" style="width: 30px;" alt="User Image">'.'&nbsp'.$data->getResponsibleName(); /*archi*/
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
                [
                    'attribute' => 'status_id',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'value' => function (Order $data) {
                        if ($data->status_id) {
                            return $data->status->shortName;
                        }
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => Order::getStatusArray(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Cтатус', 'multiple' => false],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'statusPaid_id',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'value' => function (Order $data) {
                        if ($data->statusPaid_id) {
                            return $data->getStatusPaidName();
                        }
                    },
                    'contentOptions' => function (\common\models\Order $model, $key, $index, $column) {
                        $paidStatus=$model->getPaidStatus();
                        if ($paidStatus == \common\models\Order::NOPAID) {
                            return ['style' => 'background-color:#ea9999'];
                        } else if ($paidStatus == \common\models\Order::FULLPAID) {
                            return ['style' => 'background-color:#b6d7a8'];
                        } else if ($paidStatus == \common\models\Order::PARTPAID) {
                            return ['style' => 'background-color:#ffe599'];
                        } else if ($paidStatus == \common\models\Order::OVAERPAID) {
                            return ['style' => 'background-color:#ea9999'];
                        }
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => Order::getStatusPaidsArray(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Cтатус', 'multiple' => false],
                    'format' => 'raw',
                ],
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
