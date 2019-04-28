<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Все заказы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Новый заказ'), '#', ['class' => 'btn btn-success btn-flat createNewOrder']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'id' => 'order-index-grid',
            'pjax' => true,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
//                'id',
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
                        'separator' => '.',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'todayBtn' => true,
                        ],
                    ]),
                    'contentOptions' => function (\common\models\Order $model, $key, $index, $column) {
                        $dateBegin=strtotime($model->dateBegin);
                        $date=strtotime("now");
                        $date1=strtotime("+7 day");
                        $date2=strtotime("+14 day");
                        $date3=strtotime("+21 day");
                        if ($dateBegin >= $date) {
                            if ($dateBegin <= $date1) {
                                return ['style' => 'background-color:#ff0000'];
                            } else if ($dateBegin <= $date2) {
                                return ['style' => 'background-color:#ffff00'];
                            } else if ($dateBegin <= $date3) {
                                return ['style' => 'background-color:#008000'];
                            }
                        }
                    },
                ],
                [
                    'attribute' => 'name',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->name), Url::to(['update', 'id' => $data->id]),['data-pjax'=>0]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'responsible_id',
                    'value' => function (\common\models\Order $data) {
                        if ($data->responsible) {
                            return $data->responsible->getShortName();
                        } else {
                            return '';
                        }

                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status_id',
                    'value' => function (\common\models\Order $data) {
                        if ($data->status_id) {
                            return $data->status->shortName;
                        }

                    }
                ],
                'description',

                ['class' => 'yii\grid\ActionColumn'],
            ],

        ]); ?>
    </div>
</div>
