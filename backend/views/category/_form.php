<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map($categories, 'id', 'name'), ['prompt' => Yii::t('app', 'Корень')]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'is_active')->dropDownList([ 'active' => 'активен', 'inactive' => 'Inactive', 'deleted' => 'Deleted', ], ['prompt' => '']) ?>

        <?= $form->field($model, 'client_id')->dropDownList(ArrayHelper::map($clients, 'id', 'name'), ['prompt' => 'Выберите клиента']) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
