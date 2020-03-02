<?php
use yii\helpers\Html;
/** @var $model \common\models\Product */
?>

<div class="col-md-3 col-lg-3 col-sm-4 col-xs-12">
    <div class="list__thumb">
        <a href="<?=$model->getUrl()?>">
            <img src="<?=$model->getThumb()?>" alt="list images">
        </a>
    </div>
</div>
<div class="col-md-9 col-lg-9 col-sm-8 col-xs-12">
    <div class="list__details__inner">
        <h2><a href="<?=$model->getUrl()?>"><?=$model->name?></a></h2>
        <p><?=$model->description?></p>
        <?php if ($model->priceRent) { ?>
            <span class="product__price"><?=$model->priceRent?> руб./сутки</span>
        <?php } ?>
        <?php if ($model->priceSale) { ?>
            <span class="product__price"><?=$model->priceSale?> руб.</span>
        <?php } ?>
        <div class="shop__btn">
            <a class="htc__btn" href="cart.html"><span class="ti-shopping-cart"></span>Добавить в корзину</a>
        </div>
    </div>
</div>
