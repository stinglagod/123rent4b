<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \rent\forms\manage\Client\Site\SiteForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'urlInstagram')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'urlTwitter')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'urlFacebook')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'urlGooglePlus')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'urlVk')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'urlOk')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'timezone')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
