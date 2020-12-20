<?php
/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $site \rent\entities\Client\Site */
/* @var $model \rent\forms\manage\Client\Site\SiteForm */
/* @var $form \yii\widgets\ActiveForm */
?>
СЕО
<?= $form->field($model->seo, 'title')->textInput(['maxlength' => true]) ?>
<?= $form->field($model->seo, 'keywords')->textarea(['rows'=>'10','maxlength' => true]) ?>
<?= $form->field($model->seo, 'description')->textarea(['rows'=>'10','maxlength' => true]) ?>