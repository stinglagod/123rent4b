<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ActionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="action-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'sing') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'shortName') ?>

    <?php // echo $form->field($model, 'sequence') ?>

    <?php // echo $form->field($model, 'order') ?>

    <?php // echo $form->field($model, 'antipod_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
