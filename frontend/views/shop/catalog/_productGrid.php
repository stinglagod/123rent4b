<?php

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */

use rent\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$url = Url::to(['product', 'id' =>$product->id]);

?>
<div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
    <div class="product">
        <div class="product__inner">
            <div class="pro__thumb">
                <?php if ($product->mainPhoto): ?>
                    <a href="<?=Html::encode($url)?>">
                        <img src="<?=Html::encode($product->mainPhoto->getThumbFileUrl('file', 'catalog_list'))?>" alt="">
                    </a>
                <?php endif; ?>
            </div>
            <div class="product__hover__info">
                <ul class="product__action">
                    <li><a title="В корзину" href="<?= Url::to(['/shop/cart/add', 'id' => $product->id]) ?>"><span class="ti-shopping-cart"></span></a></li>
                    <li><a title="В желания" href="<?= Url::to(['/cabinet/wishlist/add', 'id' => $product->id]) ?>" data-method="post"><span class="ti-heart"></span></a></li>
                </ul>
            </div>
        </div>
        <div class="product__details">
            <h2><a href="<?=Html::encode($url)?>"><?= Html::encode($product->name) ?></a></h2>
            <ul class="product__price">
                <?php if ($product->priceRent_new) : ?>
                    <li class="new__price"><?=PriceHelper::format($product->priceRent_new)?> руб./сутки</li>
                <?php endif; ?>
                <?php if ($product->priceSale_new) : ?>
                    <li class="new__price"><?=PriceHelper::format($product->priceSale_new)?> руб.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>