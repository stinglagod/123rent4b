<?php

use backend\forms\CRM\ContactSearch;
use rent\entities\CRM\Contact;
use rent\helpers\ContactHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use \rent\helpers\ClientHelper;
/* @var $this yii\web\View */
/* @var $searchModel ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modalCreateForm string */

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Создать контакт', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <button type="button" class="btn btn-success" title="Создать контакт" data-toggle="modal" data-target="#_modalCreate">Создать контакт в окне</button>
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
                    'value' => function (Contact $model) {
                        return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'surname',
                    'value' => function (Contact $model) {
                        return Html::a(Html::encode($model->surname), ['view', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'patronymic',
                    'value' => function (Contact $model) {
                        return Html::a(Html::encode($model->patronymic), ['view', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'telephone',
                    'value' => function (Contact $model) {
                        return Html::a(Html::encode($model->telephone), ['view', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'filter' => ContactHelper::statusList(),
                    'value' => function (Contact $model) {
                        return ContactHelper::statusLabel($model->status);
                    },
                    'format' => 'raw',
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>

<?php if ($modalCreateForm) :?>
    <?=$modalCreateForm?>
<?endif;?>

