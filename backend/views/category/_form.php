<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>
<pre><?print_r($model->errors)?></pre>
<div class="catalog-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sub') ->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Category::find() ->orderBy(['tree'=> SORT_ASC, 'lft'=> SORT_ASC]) ->all(),'id',function($model) {return '|'.str_repeat('-', $model->depth).$model['name'];} ),['prompt'=>'Создать новое меню','value'=>$parent?$parent->id:null]) ->label('Родитель') ?>﻿

        <?= $form->field($model, 'client_id')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
