<?php
/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $site \rent\entities\Client\Site */
/* @var $model \rent\forms\manage\Client\SiteForm */
/* @var $form \yii\widgets\ActiveForm */

?>
<?= $form->field($model, 'urlInstagram')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'urlTwitter')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'urlFacebook')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'urlGooglePlus')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'urlVk')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'urlOk')->textInput(['maxlength' => true]) ?>
