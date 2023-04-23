<?php

use rent\entities\Shop\Service;
use rent\helpers\ServiceHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model rent\forms\manage\Shop\BrandForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Общее</div>
        <div class="box-body">
            <?= $form->field($model, 'name')

                ->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'percent')
                ->label(Service::getLabelByAttribute('percent').
                    ServiceHelper::popoverX_byAttribute('percent',$model->getAttributeLabel('percent')))
                ->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'defaultCost')
                ->label(Service::getLabelByAttribute('defaultCost').
                    ServiceHelper::popoverX_byAttribute('defaultCost',$model->getAttributeLabel('defaultCost')))
                ->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'is_depend')
                ->label(Service::getLabelByAttribute('is_depend').
                    ServiceHelper::popoverX_byAttribute('is_depend',$model->getAttributeLabel('is_depend')))
                ->checkbox() ?>
            <?php if (Yii::$app->user->can('super_admin')) :?>
                <?= $form->field($model, 'status')->dropDownList(Service::statusList(), ['prompt' => 'Выберите','disabled' => false]) ?>
            <?else:?>
                <?= $form->field($model, 'status')->dropDownList(Service::statusListUser(), ['prompt' => 'Выберите','disabled' => false]) ?>
            <?endif;?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
