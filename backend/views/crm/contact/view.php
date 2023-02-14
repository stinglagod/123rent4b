<?php

use rent\entities\CRM\Contact;
use rent\helpers\ContactHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use \rent\helpers\ClientHelper;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use rent\entities\Client\Site;

/* @var $this yii\web\View */
/* @var $model Contact */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Контакты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-view">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="client-view box box-primary">
        <div class="box-header">
            Common
        </div>
        <div class="box-body table-responsive no-padding">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    'surname',
                    'patronymic',
                    'telephone',
                    'telephone',
                    'email',
                    'note',
                    'created_at',
                    'updated_at',
                    'author_id',
                    'lastChangeUser_id',
                    [
                        'attribute' => 'status',
                        'value' => ContactHelper::statusLabel($model->status),
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
