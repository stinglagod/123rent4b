<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model rent\forms\manage\Shop\CategoryForm */
/* @var $form yii\widgets\ActiveForm */

$categoryUrl=['category', 'id' =>$model->_category->id];
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <?= Html::a('Отмена', $categoryUrl, ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= $form->field($model, 'parentId')->dropDownList($model->parentCategoriesList()) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'showWithoutGoods')->checkbox() ?>
            <?= $form->field( $model->sites, 'others')->widget(Select2::class, [
                'data' => $model->sites->sitesList(),
                'options' => ['placeholder' => '', 'multiple' => true,],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>


        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?= $form->field($model->meta, 'title')->textInput() ?>
            <?= $form->field($model->meta, 'description')->textarea(['rows' => 2]) ?>
            <?= $form->field($model->meta, 'keywords')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::a('Отмена', $categoryUrl, ['class' => 'btn btn-default']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
