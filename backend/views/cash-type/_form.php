<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \rent\entities\Shop\Order\PaymentType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cash-type-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
