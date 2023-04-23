<?php

use rent\entities\Shop\Service;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $service rent\entities\Shop\Service */

$this->title = $service->name;
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="service-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $service->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $service->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-header with-border">Общее</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $service,
                'attributes' => [
//                    'id',
                    'name',
                    'percent',
                    [
                        'attribute' => 'is_depend',
                        'value' => function (Service $model) {

                            return $model->is_depend?'<i class="fa fa-check" aria-hidden="true"></i>':'';
                        },
                        'format' => 'raw',
                    ],
                    'defaultCost',
                    [
                        'attribute' => 'status',
                        'value' => function (Service $model) {

                            return Service::statusName($model->status);
                        },
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
