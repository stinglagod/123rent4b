<?php

use yii\helpers\Url;
use kartik\date\DatePicker;
use rent\entities\CRM\Contact;
use rent\entities\Shop\Order\Order;
use rent\entities\Shop\Order\Status;
use rent\entities\User\User;
use rent\helpers\ContactHelper;
use rent\helpers\OrderHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use \rent\helpers\ClientHelper;
use yii\bootstrap\ActiveForm;
use yii\grid\ActionColumn;
use rent\entities\Client\Site;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model Contact */
/* @var $orderSearchModel \backend\forms\Shop\OrderSearch */
/* @var $orderDataProvider yii\data\ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Контакты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="client-view box box-primary">
        <div class="box-header">
            Common
        </div>
        <div class="box-body table-responsive no-padding">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'surname',
                    'patronymic',
                    'telephone',
                    'email',
                    'note',
                    'created_at',
                    'updated_at',
                    [
                        'attribute' => 'status',
                        'value' => ContactHelper::statusLabel($model->status),
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
<div class="box-body table-responsive">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $orderDataProvider,
        'filterModel' => $orderSearchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'id' => 'order-index-grid',
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
//            'pjax' => true,
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
                'format' => ['date', 'php:D, d F Y'],
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'width' => '25%',
                'headerOptions' => ['class' => 'kv-sticky-column'],
                'filter' => DatePicker::widget([
                    'model' => $orderSearchModel,
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
            [
                'attribute' => 'current_status',
                'width' => '5%',
                'value' => function (Order $model) {
                    return OrderHelper::statusName($model->current_status);
                },
                'filter' => $orderSearchModel::statusList(),
                'filterType' => GridView::FILTER_SELECT2,
                'format' => 'raw',
            ],
            [
                'attribute' => 'paidStatus',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'value' => function (Order $model) {
                    return OrderHelper::paidStatusName($model->paidStatus);
                },
                'contentOptions' => function (Order $model, $key, $index, $column) {
                    switch ($model->paidStatus) {
                        case Status::PAID_NO:
                            return ['style' => 'background-color:#ea9999'];
                        case Status::PAID_FULL:
                            return ['style' => 'background-color:#b6d7a8'];
                        case Status::PAID_PART:
                            return ['style' => 'background-color:#ffe599'];
                        case Status::PAID_OVER:
                            return ['style' => 'background-color:#ea9999'];

                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $orderSearchModel::paidStatusList(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => 'Cтатус', 'multiple' => false],
                'format' => 'raw',
            ],
            'note',

            ['class' => 'yii\grid\ActionColumn'],
        ],

    ]); ?>
</div>