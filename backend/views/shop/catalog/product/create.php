<?php

use kartik\widgets\FileInput;
use rent\helpers\ProductHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use rent\entities\Shop\Product\Product;

/* @var $this yii\web\View */
/* @var $model rent\forms\manage\Shop\Product\ProductCreateForm */
/* @var $category \rent\entities\Shop\Category\Category */

$this->title = 'Создание нового товара';
if ($category) {
    $this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
    foreach ($category->parents as $parent) {
        if (!$parent->isRoot()) {
            $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
        }
    }
    $this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['category', 'id' => $category->id]];
    $categoryUrl=['category', 'id' =>$category->id];
} else {
    $this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
    $categoryUrl=['index'];
}

$this->params['breadcrumbs'][] = $this->title;

//Если раздел не выводится на сайт, тогда и отображение и сайты можно скрыть
$cssSiteClass = 'product-form-site ';
if (!$model->onSite) {
    $cssSiteClass .= 'product-form-site hidden';
}
?>
<div class="product-create">
    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Отмена', $categoryUrl, ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6">
        </div>
    </div>


    <div class="box box-default">
        <div class="box-header with-border">Главное</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'code')
                        ->label(Product::getLabelByAttribute('code').ProductHelper::popoverX_code($model->getAttributeLabel('code')))
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'onSite')
                        ->label(Product::getLabelByAttribute('on_site').
                            ProductHelper::popoverX_onSite($model->getAttributeLabel('on_site')))
                        ->checkbox() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->priceCost, 'cost')
                        ->label(Product::getLabelByAttribute('priceCost').
                            ProductHelper::popoverX_priceCost($model->getAttributeLabel('priceCost')))
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->priceCompensation, 'cost')
                        ->label(Product::getLabelByAttribute('priceCompensation').
                            ProductHelper::popoverX_priceCompensation($model->getAttributeLabel('priceCompensation')))
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->priceRent, 'new')
                        ->label(Product::getLabelByAttribute('priceRent_new').
                            ProductHelper::popoverX_priceRent_new($model->getAttributeLabel('priceRent_new')))
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->priceSale, 'new')
                        ->label(Product::getLabelByAttribute('priceSale_new').
                            ProductHelper::popoverX_priceSale_new($model->getAttributeLabel('priceSale_new')))
                        ->textInput(['maxlength' => true]) ?>
                </div>

            </div>
            <?= $form->field($model, 'description')
                ->label(Product::getLabelByAttribute('description').
                    ProductHelper::popoverX_description($model->getAttributeLabel('description')))
                ->textarea(['rows' => 10]) ?>
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
        <div class="col-md-4 <?=$cssSiteClass?>">
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
    </div>

    <div class="box box-default">
        <div class="box-header with-border">
            Характеристики
            <?=ProductHelper::defaultPopoverX('Характеристики','<a href="/admin/shop/characteristic/" target="_blank">Добавить новые поля</a>')?>
        </div>
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
        <div class="box-header with-border">Изображения</div>
        <div class="box-body">
            <?= $form->field($model->photos, 'files[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => true,
                ]
            ]) ?>
        </div>
    </div>

    <div class="box box-default <?=$cssSiteClass?>">
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

<?php
$js=<<<JS
//Открываем или скрываем настройки для отображения на сайте
$("body").on("change", '#productcreateform-onsite', function() {
    if (this.checked) {
        $('.product-form-site').removeClass('hidden');
    } else {
        $('.product-form-site').addClass('hidden');
    }
})
JS;
$this->registerJs($js);
?>
