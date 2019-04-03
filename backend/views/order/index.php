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
                    'width' => '20%',
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
                ],
                [
                    'attribute' => 'name',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->name), Url::to(['update', 'id' => $data->id]),['data-pjax'=>0]);
                    },
                    'format' => 'raw',
                ],

//                'dateEnd',
                // 'customer',
                // 'address',
                // 'description',
                // 'created_at',
                // 'updated_at',
                // 'autor_id',
                // 'lastChangeUser_id',
                // 'is_active',
                // 'client_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
