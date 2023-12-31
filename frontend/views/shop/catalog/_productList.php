<?php

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */

use rent\entities\Shop\Order\Item\OrderItem;
use rent\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$url = Url::to(['product', 'id' =>$product->id]);

?>
<!-- Start List Content-->
<div class="single__list__content clearfix">
    <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12">
        <div class="list__thumb">
            <?php if ($product->mainPhoto): ?>
                <a href="<?=Html::encode($url)?>">
                    <img src="<?=Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list'))?>" alt="list images">
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-9 col-lg-9 col-sm-8 col-xs-12">
        <div class="list__details__inner">
            <h2><a href="<?=Html::encode($url)?>"><?= Html::encode($product->name) ?></a></h2>
            <p><?= Html::encode(StringHelper::truncateWords(strip_tags($product->description), 20)) ?></p>
            <?php if ($product->priceRent_new) : ?>
                <span class="product__price"><?=PriceHelper::format($product->priceRent_new)?> руб./сутки</span>
            <?php endif; ?>
            <?php if ($product->priceSale_new) : ?>
                <span class="product__price"><?=PriceHelper::format($product->priceSale_new)?> руб.</span>
            <?php endif; ?>
            <div class="shop__btn">
                <?php if ($product->canRent()) :?>
                    <a title="Аренда" class="htc__btn btn-add-ajax" href="<?= Url::to(['/shop/cart/add-ajax', 'id' => $product->id,'type'=>OrderItem::TYPE_RENT]) ?>"><span class="ti-reload"> Арендовать</span></a>
                <?php endif;?>
                <?php if ($product->canSale()) :?>
                    <a title="Купить" class="htc__btn btn-add-ajax" href="<?= Url::to(['/shop/cart/add-ajax', 'id' => $product->id,'type'=>OrderItem::TYPE_SALE]) ?>"><span class="ti-shopping-cart">Купить</span></a>
                <?php endif;?>
                <a class="htc__btn btn-add-ajax" href="<?= Url::to(['/cabinet/wishlist/add', 'id' => $product->id]) ?>"><span class="ti-heart"></span>Добавить в желания</a>
            </div>
        </div>
    </div>
</div>
