<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use rent\entities\Client\Client;
use kartik\file\FileInput;
use yii\web\JsExpression;
use yii\widgets\Pjax;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model \rent\forms\manage\User\UserEditForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
<!--            --><?//= ((\Yii::$app->user->can('manager')))?$form->field($model, 'role')->dropDownList($model->RoleTypes,['multiple' => true,]):''?>
        </div>
        <div class="col-md-6">
            <?php Pjax::begin(['id' => 'pjax_avatar']); ?>
            <img src="<?=$model->_user->avatarUrl?>" class="img-circle center-block" style="width: 100px;" alt="User Image">
            <?php Pjax::end(); ?>
            <?= $form->field($model, 'avatar')->label(false)->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                ],
                'pluginOptions' => [
                    'showPreview' => false,
                ],
            ]) ?>
        </div>
    </div>



    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'surname')->textInput() ?>

    <?= $form->field($model, 'patronymic')->textInput() ?>

    <?= $form->field($model, 'telephone')->textInput() ?>

    <?= $form->field($model, 'default_site')->widget(Select2::class, [
        'data' => $model->getSiteList(),
        'options' => ['placeholder' => '', 'multiple' => false,],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>



    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
