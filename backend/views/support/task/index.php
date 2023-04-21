<?php

use backend\forms\support\TaskSearch;
use rent\entities\Shop\Brand;
use rent\entities\Support\Task\Task;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки тех. поддержки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="support-index">

    <p>
        <?= Html::a('Создать заявку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'name',
                        'value' => function (Task $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'text',
                        'value' => function (Task $model) {
                            return mb_strimwidth($model->text, 0, 50, "...");;
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'priority',
                        'value' => function (Task $model) {
                            return Task::getPriorityLabel($model->priority);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'type',
                        'value' => function (Task $model) {
                            return Task::getTypeLabel($model->type);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (Task $model) {
                            return Task::getStatusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
