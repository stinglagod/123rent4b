<?php
use yii\helpers\Html;
/** @var $model \common\models\Product */
?>

<div class="product">
    <div class="product__inner">
        <div class="pro__thumb">
            <a href="<?=$model->getUrl()?>">
                <img src="<?=$model->getThumb()?>" alt="<?=$model->name?>">
            </a>
        </div>
<!--        <div class="product__hover__info">-->
<!--            <ul class="product__action">-->
<!--                <li><a data-toggle="modal" data-target="#productModal" title="Быстрый просмотре" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>-->
<!--                <li><a title="В корзину" href="cart.html"><span class="ti-shopping-cart"></span></a></li>-->
<!--                <li><a title="В желания" href="wishlist.html"><span class="ti-heart"></span></a></li>-->
<!--            </ul>-->
<!--        </div>-->
    </div>
    <div class="product__details">
        <h2><a href="<?=$model->getUrl()?>"><?=$model->name?></a></h2>
        <ul class="product__price">
            <?php if ($model->priceRent) { ?>
                <li class="new__price"><?=$model->priceRent?> руб./сутки</li>
            <?php } ?>
            <?php if ($model->priceSale) { ?>
                <li class="new__price"><?=$model->priceSale?> руб.</li>
            <?php } ?>
        </ul>
    </div>
</div>
