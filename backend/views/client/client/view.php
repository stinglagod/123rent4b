<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \rent\helpers\ClientHelper;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \rent\entities\Client\Client */
/* @var $invite \rent\forms\manage\Client\InviteForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-view">
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
                    [
                        'attribute' => 'status',
                        'value' => ClientHelper::statusLabel($model->status),
                        'format' => 'raw',
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <div class="client-view box box-primary" id="users">
        <div class="box-header">
            Users
        </div>
        <div class="box-body">
            <div class="row">
                <?php $form = ActiveForm::begin([
//                    'action'=>['invitation'],
                    'method' => 'post',
                ]); ?>
                <div class="col-md-4">
                    <?= $form->field($invite, 'name')->label(true)->textInput(['maxLength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($invite, 'email')->label(true)->textInput(['maxLength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?= Html::submitButton('Пригласить', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Имя</th>
                                <th scope="col">Email</th>
                                <th scope="col">Владелец</th>
                                <th scope="col">Операции</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($model->users as $user): ?>
                            <tr>
                                <td><?=$user->getShortName()?></td>
                                <td><?=$user->email?></td>
                                <td><?=$user->email?></td>
                                <td>
                                    <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-user', 'id' => $model->id, 'user_id' => $user->id], [
                                        'class' => 'btn btn-default',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Удалить пользователя из списка?',
                                    ]); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
