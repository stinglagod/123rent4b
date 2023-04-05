<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */
/* @var $model rent\forms\manage\Shop\Product\ProductEditForm */

$categoryUrl=['category', 'id' =>$product->category->id];
?>

<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <?= Html::a('Отмена', $categoryUrl, ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<div class="box box-default">
    <div class="box-header with-border">Главное</div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'onSite')->checkbox() ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model->priceCost, 'cost')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model->priceCompensation, 'cost')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model->priceRent, 'new')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model->priceSale, 'new')->textInput(['maxlength' => true]) ?>
            </div>

        </div>
        <div class="row">

        </div>
        <?= $form->field($model, 'description')->textarea(['rows' => 10]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="box box-default">
            <div class="box-header with-border">Категории</div>
            <div class="box-body">
                <?= $form->field( $model->categories, 'main')->widget(Select2::class, [
                    'data' => $model->categories->categoriesList(),
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                <?= $form->field( $model->categories, 'others')->widget(Select2::class, [
                    'data' => $model->categories->categoriesList(),
                    'options' => ['placeholder' => '', 'multiple' => true,],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-default">
            <div class="box-header with-border">Сайты</div>
            <div class="box-body">
                <?= $form->field( $model->sites, 'others')->widget(Select2::class, [
                    'data' => $model->sites->sitesList(),
                    'options' => ['placeholder' => '', 'multiple' => true,],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-default">
            <div class="box-header with-border">Теги</div>
            <div class="box-body">
                <?=
                $form->field($model->tags, 'existing')->widget(Select2::class, [
                    'data' => $model->tags->tagsList(),
                    'options' => ['placeholder' => '', 'multiple' => true,],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [','],
                        'maximumInputLength' => 20,
                        'allowClear' => true,
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">Характеристики</div>
    <div class="box-body">
        <?php foreach ($model->values as $i => $value): ?>
            <?php if ($variants = $value->variantsList()): ?>
                <?= $form->field($value, '[' . $i . ']value')->dropDownList($variants, ['prompt' => '']) ?>
            <?php else: ?>
                <?= $form->field($value, '[' . $i . ']value')->textInput() ?>
            <?php endif ?>
        <?php endforeach; ?>
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
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

