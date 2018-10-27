<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a("Создать нового пользователя", ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // 'status',
                // 'created_at',
                // 'updated_at',
                [
                    'attribute' => 'shortName',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->shortName), Url::to(['update', 'id' => $data->id]));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'role',
                    'value' => 'roleArray'
                ],
                 'email:email',
                [
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'dateCreate_from',
                        'attribute2' => 'dateCreate_to',
                        'type' => DatePicker::TYPE_RANGE,
                        'separator' => '-',
                        'pluginOptions' => ['format' => 'yyyy-mm-dd']
                    ]),
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                ],
                [
                    'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'dateUpdate_from',
                        'attribute2' => 'dateUpdate_to',
                        'type' => DatePicker::TYPE_RANGE,
                        'separator' => '-',
                        'pluginOptions' => ['format' => 'yyyy-mm-dd']
                    ]),
                    'attribute' => 'updated_at',
                    'format' => 'datetime',
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
