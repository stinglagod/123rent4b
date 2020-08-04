<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */
/* @var $model rent\forms\manage\Shop\Product\ProductEditForm */

$this->title = 'Редактирование товара: ' . $product->name;

foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = $product->name;
?>
<div class="product-update">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Главное</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-8">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->priceCost, 'cost')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
<!--                    --><?//= $form->field($model->priceRent, 'new')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model->priceRent, 'new')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->priceSale, 'new')->textInput(['maxlength' => true]) ?>
                </div>

            </div>
            <div class="row">

            </div>
            <?= $form->field($model, 'description')->textarea(['rows' => 10]) ?>
        </div>
    </div>
<?php
//var_dump($model->categories->others);exit;
?>
    <div class="row">
        <div class="col-md-6">
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
                    <?=Select2::widget([
                        'model' => $model->categories,
                        'attribute' => 'main',
                        'data' => $model->categories->categoriesList(),
                        'options' => ['placeholder' => ''],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
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
        <div class="col-md-6">
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

</div>
