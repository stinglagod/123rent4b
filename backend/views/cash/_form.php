<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Cash */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dateTime')->textInput() ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'lastChangeUser_id')->textInput() ?>

    <?= $form->field($model, 'client_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
