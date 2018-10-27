<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderProductAction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-product-action-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_product_id')->textInput() ?>

    <?= $form->field($model, 'movement_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
