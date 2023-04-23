<?php

use kartik\popover\PopoverX;
use rent\entities\Shop\Product\Modification;
use rent\entities\Shop\Product\Value;
use rent\helpers\ImageHelper;
use rent\helpers\PriceHelper;
use rent\helpers\ProductHelper;
use yii\bootstrap\ActiveForm;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use rent\entities\Shop\Order\Item\OrderItem;

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */
/* @var $photosForm rent\forms\manage\Shop\Product\PhotosForm */
/* @var $modificationsProvider yii\data\ActiveDataProvider */

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = $product->name;

$balance = $product->getQuantity();
?>
<div class="user-view">

    <div class="row">
        <div class="col-md-6">
        <p>
            <?php if ($product->isActive()): ?>
                <?= Html::a('Деактивировать', ['product-draft', 'id' => $product->id], ['class' => 'btn btn-warning', 'data-method' => 'post']) ?>
            <?php else: ?>
                <?= Html::a('Активировать', ['product-activate', 'id' => $product->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
            <?php endif; ?>
            <?= Html::a('Редактировать', ['product-update', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['product-delete', 'id' => $product->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы действительно хотите удалить этот товар',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
        </div>
        <div class="col-md-6">
<!--            --><?php //= $product->canRent() ? Html::a('Арендовать', ['shop/order/item-add-ajax', 'product_id' => $product->id,'type_id'=>OrderItem::TYPE_RENT], ['class' => 'btn btn-info add2order', 'data-method' => 'post', 'data-qty' => $balance]):null ?>
<!--            --><?php //= $product->canSale() ? Html::a('Купить', ['shop/order/item-add-ajax', 'product_id' => $product->id,'type_id'=>OrderItem::TYPE_RENT], ['class' => 'btn btn-warning add2order', 'data-method' => 'post', 'data-qty' => $balance]):null ?>
            <?= Html::a('Добавить движение(изменение остатков)', ['product-movement', 'id' => $product->id], ['class' => 'btn btn-info'], ['class' => 'btn btn-warning ', 'data-method' => 'post', 'data-qty' => $balance])?>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">Изображения</div>
                <div class="box-body">
                    <?php if ($product->photos):?>
                        <ul id="imageGallery">
                            <?php foreach ( $product->photos as $photo): ?>
                                <li data-thumb='<?=$photo->getThumbFileUrl('file', 'backend_thumb')?>' data-src='<?=$photo->getThumbFileUrl('file', 'backend_thumb')?>'>
                                    <img src='<?=$photo->getThumbFileUrl('file', 'backend_thumb')?>' class='center-block'/>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    <?else:?>
                        <img src='<?= ImageHelper::getEmptyImage()?>' class='center-block'/>
                    <?php endif;?>

                </div>
                <div class="box-footer">
                    <?= Html::a('Редактировать', '#photos', ['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">Общая информация</div>
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $product,
                        'attributes' => [
                            'name',
                            [
                                'attribute' => 'code',
                                'format' => 'raw',
                                'label' => $product->getAttributeLabel('code').
                                    ProductHelper::popoverX_code($product->getAttributeLabel('code')),
                            ],
                            [
                                'attribute' => 'status',
                                'value' => ProductHelper::statusLabel($product->status),
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'on_site',
                                'value' => ProductHelper::onSiteLabel($product->on_site),
                                'label' => $product->getAttributeLabel('on_site').
                                    ProductHelper::popoverX_onSite($product->getAttributeLabel('on_site'))
                                ,
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'priceSale_new',
                                'value' => PriceHelper::format($product->priceSale_new),
                                'label' => $product->getAttributeLabel('priceSale_new').
                                    ProductHelper::popoverX_priceSale_new($product->getAttributeLabel('priceSale_new')),
                            ],
                            [
                                'attribute' => 'priceRent_new',
                                'value' => PriceHelper::format($product->priceRent_new),
                                'label' => $product->getAttributeLabel('priceRent_new').
                                    ProductHelper::popoverX_priceRent_new($product->getAttributeLabel('priceRent_new')),
                            ],
                            [
                                'label'=> 'Всего на складе'.
                                    ProductHelper::popoverX_balanceWarehouse('Всего на складе'),
                                'value' => $product->getQuantity()
                            ],
                            [
                                'label'=> 'Свободно для заказа'.
                                    ProductHelper::popoverX_balanceWarehouseWithRent('Всего на складе'),
                                'value' => Html::a($product->getQuantity().' шт. (подробнее)', ['product-movement', 'id' => $product->id], ['class' => 'btn btn->default']),
                                'format' => 'raw',
                            ]
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
        <div class="box-header with-border">Цены</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $product,
                'attributes' => [
                    [
                        'attribute' => 'priceSale_new',
                        'value' => PriceHelper::format($product->priceSale_new),
                        'label' => $product->getAttributeLabel('priceSale_new').
                            ProductHelper::popoverX_priceSale_new($product->getAttributeLabel('priceSale_new')),
                    ],
                    [
                        'attribute' => 'priceSale_old',
                        'value' => PriceHelper::format($product->priceSale_old),
                        'label' => $product->getAttributeLabel('priceSale_old').
                            ProductHelper::popoverX_priceSale_old($product->getAttributeLabel('priceSale_old')),
                    ],
                    [
                        'attribute' => 'priceRent_new',
                        'value' => PriceHelper::format($product->priceRent_new),
                        'label' => $product->getAttributeLabel('priceRent_new').
                            ProductHelper::popoverX_priceRent_new($product->getAttributeLabel('priceRent_new')),
                    ],
                    [
                        'attribute' => 'priceRent_old',
                        'value' => PriceHelper::format($product->priceRent_old),
                        'label' => $product->getAttributeLabel('priceRent_old').
                            ProductHelper::popoverX_priceRent_old($product->getAttributeLabel('priceRent_old')),
                    ],
                    [
                        'attribute' => 'priceCost',
                        'value' => PriceHelper::format($product->priceCost),
                        'label' => $product->getAttributeLabel('priceCost').
                            ProductHelper::popoverX_priceCost($product->getAttributeLabel('priceCost')),
                    ],
                    [
                        'attribute' => 'popoverX_priceCompensation',
                        'value' => PriceHelper::format($product->priceCompensation),
                        'label' => $product->getAttributeLabel('priceCompensation').
                            ProductHelper::popoverX_priceCompensation($product->getAttributeLabel('priceCompensation')),
                    ],
                ],
            ]) ?>
            <br />
        </div>
    </div>
        </div>

        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    Характеристики
                    <?=ProductHelper::defaultPopoverX('Характеристики','<a href="/admin/shop/characteristic/" target="_blank">Добавить новые поля</a>')?>
                </div>
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $product,
                        'attributes' => array_map(function (Value $value) {
                            return [
                                'label' => $value->characteristic->name,
                                'value' => $value->value,
                            ];
                        }, $product->values),
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">Описание</div>
        <div class="box-body">
            <?= Yii::$app->formatter->asNtext($product->description) ?>
        </div>
    </div>



    <div class="box" id="photos">
        <div class="box-header with-border">Изображения</div>
        <div class="box-body">

            <div class="row">
                <?php foreach ($product->photos as $photo): ?>
                    <div class="col-md-2 col-xs-3" style="text-align: center">
                        <div class="btn-group">
                            <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-photo-up', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); ?>
                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                                'data-confirm' => 'Удалить изображение?',
                            ]); ?>
                            <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', ['move-photo-down', 'id' => $product->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); ?>
                        </div>
                        <div>
                            <?= Html::a(
                                Html::img($photo->getThumbFileUrl('file', 'thumb')),
                                $photo->getUploadedFileUrl('file'),
                                ['class' => 'thumbnail', 'target' => '_blank']
                            ) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php $form = ActiveForm::begin([
                'options' => ['enctype'=>'multipart/form-data'],
            ]); ?>

            <?= $form->field($photosForm, 'files[]')->label(false)->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => true,
                ]
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Загрузка', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

    <? if ($product->on_site) :?>
    <div class="box">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $product,
                'attributes' => [
                    [
                        'attribute' => 'meta.title',
                        'value' => $product->meta->title,
                    ],
                    [
                        'attribute' => 'meta.description',
                        'value' => $product->meta->description,
                    ],
                    [
                        'attribute' => 'meta.keywords',
                        'value' => $product->meta->keywords,
                    ],
                ],
            ]) ?>
        </div>
    </div>
    <?endif;?>
</div>
<?php

$js = <<<JS
    $('#imageGallery').lightSlider({
        gallery:true,
        item:1,
        loop:true,
        thumbItem:9,
        slideMargin:0,
        enableDrag: false,
        currentPagerPosition:'center',
        onSliderLoad: function(el) {
            el.lightGallery({
                selector: '#imageGallery .lslide'
            });
        }   
    });
JS;
$this->registerJs($js);
?>