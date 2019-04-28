<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Status */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="status-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'shortName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'hand')->textInput() ?>

        <?= $form->field($model, 'order')->textInput() ?>

        <?= $form->field($model, 'action_id')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
