<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList([ 'rent' => 'Rent', 'sale' => 'Sale', 'service' => 'Service', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'set')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'dateBegin')->textInput() ?>

    <?= $form->field($model, 'dateEnd')->textInput() ?>

    <?= $form->field($model, 'period')->textInput() ?>

    <?= $form->field($model, 'periodType_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
