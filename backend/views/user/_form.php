<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use rent\entities\Client\Client;
use kartik\file\FileInput;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model \rent\entities\User\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= ((\Yii::$app->user->can('manager')))?$form->field($model, 'role')->dropDownList($model->RoleTypes,['multiple' => true,]):''?>
        </div>
        <div class="col-md-6">
            <?php Pjax::begin(['id' => 'pjax_avatar']); ?>
            <img src="<?=$model->avatarUrl?>" class="img-circle center-block" style="width: 100px;" alt="User Image">
            <?php Pjax::end(); ?>
            <?=FileInput::widget([
                'name'=>'file[]',
                'options' => ['multiple' => false, 'accept' => 'image/*'],
                'pluginOptions' => [
                    'showPreview' => false,
                    'uploadUrl' => \yii\helpers\Url::to(['user/upload-avatar','id'=>$model->id]),
                    'uploadExtraData' => new JsExpression("function (previewId, index) {
                    return {
                        hash: '$model->hash',
                    };
                }"),
                ],

                'pluginEvents' => [
                    "fileuploaded" => "function() { 
                        $.pjax.reload({container: \"#pjax_alerts\", async: false});
                        $.pjax.reload({container: \"#pjax_avatar\", async: false});
                    }",
                ],

            ]);?>
        </div>
    </div>



    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'surname')->textInput() ?>

    <?= $form->field($model, 'patronymic')->textInput() ?>

    <?= $form->field($model, 'telephone')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['readonly' => (!$model->isNewRecord)]) ?>

    <?= $form->field($model, 'client_id')->dropDownList(ArrayHelper::map($clients, 'id', 'name'), ['prompt' => Yii::t('app', 'Выберите')]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?php
        if (!($model->isNewRecord)) {
            HTML::a('Сбросить пароль пользователя', ['site/request-password-reset-by-id', 'id' => $model->id],['class' => 'btn btn-primary']);
        }?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
