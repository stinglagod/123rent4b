<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Все заказы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index box box-primary">
    <?php Pjax::begin(); ?>
<!--    <div class="box-header with-border">-->
<!--        --><?//= Html::a(Yii::t('app', 'Create Order'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
<!--    </div>-->
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
//                'id',
                'cod',[
                    'attribute' => 'name',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->name), Url::to(['update', 'id' => $data->id]),['data-pjax'=>0]);
                    },
                    'format' => 'raw',
                ],
                'dateBegin',
                'dateEnd',
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
    <?php Pjax::end(); ?>
</div>
