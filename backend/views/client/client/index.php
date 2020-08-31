<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use rent\entities\Client\Client;
use \rent\helpers\ClientHelper;
/* @var $this yii\web\View */
/* @var $searchModel \backend\forms\Client\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Создать клиента', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
<!--        --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                'id',
                [
                    'attribute' => 'created_at',
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
                    'format' => 'datetime',
                ],
                [
                    'attribute' => 'name',
                    'value' => function (Client $model) {
                        return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'filter' => ClientHelper::statusList(),
                    'value' => function (Client $model) {
                        return ClientHelper::statusLabel($model->status);
                    },
                    'format' => 'raw',
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
