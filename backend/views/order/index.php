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
            <div class="col-md-3">
                <?= Html::a(Yii::t('app', 'Новый заказ'), '#', ['class' => 'btn btn-success btn-flat createNewOrder']) ?>
            </div>
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1
                ],
            ]); ?>
            <div class="col-md-3">

                <div class="form-group">
                    <?= $form->field($searchModel, 'owner')->checkbox(['class'=>'filterField']) ?>
                    <?= $form->field($searchModel, 'hideClose')->checkbox(['class'=>'filterField']) ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Поиск'), ['class' => 'btn btn-primary']) ?>
<!--                    --><?//= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

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
                ],
                [
                    'attribute' => 'dateBegin',
                    'format' => ['date', 'php:d.m.Y'],
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'width' => '15%',
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
                        $date1=strtotime("+7 day");
                        $date2=strtotime("+14 day");
                        $date3=strtotime("+21 day");
                        if ($dateBegin >= $date) {
                            if ($dateBegin <= $date1) {
                                return ['style' => 'background-color:#ea9999'];
                            } else if ($dateBegin <= $date2) {
                                return ['style' => 'background-color:#ffe599'];
                            } else if ($dateBegin <= $date3) {
                                return ['style' => 'background-color:#b6d7a8'];
                            }
                        }
                    },
                ],
                [
                    'attribute' => 'name',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->name), Url::to(['update', 'id' => $data->id]),['data-pjax'=>0,'target'=>"_blank"]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'responsible_id',
                    'value' => function (Order $data) {
                        return '<img src="'.$data->responsible->avatarUrl.'" class="img-circle" style="width: 30px;" alt="User Image">'.$data->getResponsibleName();
//                        return $data->getResponsibleName();
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(User::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'hideSearch' => true,
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Менеджер', 'multiple' => false],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status_id',
                    'value' => function (Order $data) {
                        if ($data->status_id) {
                            return $data->status->shortName;
                        }
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Status::find()->orderBy('order')->asArray()->all(), 'id', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Cтатус', 'multiple' => false],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'paidStatusName',
//                    'value' => function (\common\models\Order $data) {
//                        return $data->getPaidStatus(true);
//
//                    },
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
                ],
                'description',

                ['class' => 'yii\grid\ActionColumn'],
            ],

        ]); ?>
    </div>
</div>
<?php
$js = <<<JS
    $("body").on("click", '.filterField', function(e) {
         // alert('change');
    })
     
JS;
$this->registerJs($js);

?>
