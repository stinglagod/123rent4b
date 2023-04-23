<?php

use rent\entities\Shop\Service;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Услуги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-info">
    <div class="box box-primary">
        <div class="box-header with-border">Информация</div>
        <div class="box-body">
            <h4>Дополнительные услуг</h4>
            <p>
                Данные услуги можно выбрать на странице редактирования заказа
            </p>
            <label><?=Service::getLabelByAttribute('name')?></label> - <?=Service::getDescriptionByAttribute('name')?><br>
            <label><?=Service::getLabelByAttribute('percent')?></label> - <?=Service::getDescriptionByAttribute('percent')?><br>
            <label><?=Service::getLabelByAttribute('is_depend')?></label> - <?=Service::getDescriptionByAttribute('is_depend')?><br>
            <label><?=Service::getLabelByAttribute('defaultCost')?></label> - <?=Service::getDescriptionByAttribute('defaultCost')?><br>
            <p>
                <b>Примечание:</b> <br>
                Удалять услугу используемая в не закрытом заказе невозможно.
            </p>

        </div>
    </div>
</div>
<div class="service-index">

    <p>
        <?= Html::a('Создать Услугу', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Создать Услуги по умолчанию', ['create-default'], ['class' => 'btn btn-warning']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'name',
                        'value' => function (Service $model) {
                            return Html::a(Html::encode($model->name), ['update', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'percent',
                    'defaultCost',
                    [
                        'attribute' => 'is_depend',
                        'value' => function (Service $model) {

                            return $model->is_depend?'<i class="fa fa-check" aria-hidden="true"></i>':'';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (Service $model) {

                            return Service::statusName($model->status);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}{update}{delete}{delete-force}',
                        'contentOptions' => ['class' => 'action-column'],
                        'buttons' => [
                            'delete-force' => function ($url, Service $model, $key) {
                                if (Yii::$app->user->can('super_admin'))
                                    return Html::a('<span class="text-red glyphicon glyphicon-trash"></span>', Url::toRoute(['delete-force','id'=>$model->id]), [
                                        'title' => 'Удалить на всегда',
                                        'data-confirm'=>'Вы действительно хотите удалить услугу. Операция не обратима?',
                                        'data-method'=>'post'
                                    ]);
                            },
                        ],

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
