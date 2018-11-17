<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Client;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= ((\Yii::$app->user->can('manager')))?$form->field($model, 'role')->dropDownList($model->RoleTypes,['multiple' => true,]):''?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'surname')->textInput() ?>

    <?= $form->field($model, 'patronymic')->textInput() ?>

    <?= $form->field($model, 'telephone')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['readonly' => (!$model->isNewRecord)]) ?>

    <?= $form->field($model, 'client_id')->dropDownList(ArrayHelper::map($clients, 'id', 'name'), ['prompt' => Yii::t('app', 'Выберите')]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?php
        if (!($model->isNewRecord)) {
            HTML::a('Сбросить пароль пользователя', ['site/request-password-reset-by-id', 'id' => $model->id],['class' => 'btn btn-primary']);
        }?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
