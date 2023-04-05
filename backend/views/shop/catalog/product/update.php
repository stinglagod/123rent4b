<?php

use rent\helpers\ProductHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */
/* @var $model rent\forms\manage\Shop\Product\ProductEditForm */

$this->title = 'Редактирование товара: ' . $product->name;

$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = $product->name;

$categoryUrl=['category', 'id' =>$product->category->id];

//Если раздел не выводится на сайт, тогда и отображение и сайты можно скрыть
$cssSiteClass = 'product-form-site ';
if (!$model->onSite) {
    $cssSiteClass .= 'product-form-site hidden';
}

?>
<div class="product-update">

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
                    <?= $form->field($model, 'code')
                        ->label($product->getAttributeLabel('code').ProductHelper::popoverX_code($product->getAttributeLabel('code')))
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'onSite')
                        ->label($product->getAttributeLabel('on_site').
                            ProductHelper::popoverX_onSite($product->getAttributeLabel('on_site')))
                        ->checkbox() ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->priceCost, 'cost')
                        ->label($product->getAttributeLabel('priceCost').
                            ProductHelper::popoverX_priceCost($product->getAttributeLabel('priceCost')))
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->priceCompensation, 'cost')
                        ->label($product->getAttributeLabel('priceCompensation').
                            ProductHelper::popoverX_priceCompensation($product->getAttributeLabel('priceCompensation')))
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->priceRent, 'new')
                        ->label($product->getAttributeLabel('priceRent_new').
                            ProductHelper::popoverX_priceRent_new($product->getAttributeLabel('priceRent_new')))
                        ->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model->priceSale, 'new')
                        ->label($product->getAttributeLabel('priceSale_new').
                            ProductHelper::popoverX_priceSale_new($product->getAttributeLabel('priceSale_new')))
                        ->textInput(['maxlength' => true]) ?>
                </div>

            </div>
            <div class="row">

            </div>
            <?= $form->field($model, 'description')
                ->label($product->getAttributeLabel('description').
                    ProductHelper::popoverX_description($product->getAttributeLabel('description')))
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
$("body").on("change", '#producteditform-onsite', function() {
    if (this.checked) {
        $('.product-form-site').removeClass('hidden');
    } else {
        $('.product-form-site').addClass('hidden');
    }
})
JS;
$this->registerJs($js);
?>