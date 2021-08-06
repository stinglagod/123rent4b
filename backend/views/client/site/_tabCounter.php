<?php
/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $site \rent\entities\Client\Site */
/* @var $model \rent\forms\manage\Client\Site\SiteForm */
/* @var $form \yii\widgets\ActiveForm */

?>
<?= $form->field($model->counter, 'google_tag')->textarea(['rows'=>'10','maxlength' => true]) ?>
<?= $form->field($model->counter, 'google_counter')->textarea(['rows'=>'10','maxlength' => true]) ?>
<?= $form->field($model->counter, 'yandex_counter')->textarea(['rows'=>'10','maxlength' => true]) ?>
<?= $form->field($model->counter, 'yandex_webmaster')->textarea(['rows'=>'1','maxlength' => true]) ?>
<?= $form->field($model->counter, 'facebook_pixel')->textarea(['rows'=>'10','maxlength' => true]) ?>

