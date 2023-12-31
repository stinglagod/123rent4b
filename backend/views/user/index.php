<?php

use rent\entities\User\User;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use yii\helpers\Url;
use backend\widgets\grid\RoleColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
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
                [
                    'attribute' => 'shortName',
                    'value' => function ($data) {
                        return Html::a(Html::encode($data->shortName), Url::to(['update', 'id' => $data->id]));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'shortName',
                    'value' => function ($data) {
                        return Html::a('Войти', Url::to(['sign-in', 'id' => $data->id]));
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'role',
                    'class' => RoleColumn::class,
                    'filter' => $searchModel->rolesList(),
                ],
                'email:email',
                [
                    'attribute' => 'clientsName',
                    'value' => function (User $data) {
                        $response='';
                        $clients=$data->clientsAll;
                        foreach ($clients as $client) {
                            $response.=Html::a(Html::encode($client->name), Url::to(['client/view', 'id' => $client->id]),['target'=>'_blank']).'<br>';
                        }
                        return $response;
                    },
                    'format' => 'raw',
                ],
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
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
