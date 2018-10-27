<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ostatok */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ostatok-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dateTime')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList([ 'move' => 'Move', 'rentSoft' => 'RentSoft', 'rentHard' => 'RentHard', 'repairs' => 'Repairs', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'movement_id')->textInput() ?>

    <?= $form->field($model, 'client_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
