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
use rent\helpers\OrderHelper;
use rent\entities\Shop\Order\Status;

/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\Shop\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Касса';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index box box-primary">
    <div class="box-header with-border">
        <br>
        <div class="row">
            <div class="col-md-3">
                <div class="btn-group" role="group" aria-label="toolbar">
                    <?= Html::a('Добавить движение Д/С', ['create'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="box-body table-responsive">
          <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => "{items}\n{summary}\n{pager}",
                'id' => 'cashbox-index-grid',
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
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
                    'order_id',
                    'type_id',
                    'responsible_name',
                    'payer_name',
                    'payer_phone',
                    'sum',
                    'note',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
          ]); ?>
    </div>
</div>