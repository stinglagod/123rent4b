<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $model rent\forms\manage\Shop\Product\ModificationForm */

$this->title = 'Создать сайт';
$this->params['breadcrumbs'][] = ['label' => 'Клиент', 'url' => ['client/client/index']];
$this->params['breadcrumbs'][] = ['label' => $client->name, 'url' => ['client/client/view', 'id' => $client->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-create">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'timezone')->textInput() ?>
        <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'urlInstagram')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'urlTwitter')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'urlFacebook')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'urlGooglePlus')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'urlVk')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'urlOk')->textInput(['maxlength' => true]) ?>


        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>
