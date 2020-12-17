<?php

/* @var $this yii\web\View */
/* @var $model rent\entities\Shop\Product\Product */
/* @var $order \rent\entities\Shop\Order\Order */

use rent\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use rent\entities\Shop\Order\Item\OrderItem;
use \rent\helpers\CatalogHelper;

//TODO надо бы переделать на изящнее
if ($nameLayout=CatalogHelper::getNameLayout()) {
    $url = Url::to(['product', 'id' =>$model->id,'layout'=>$nameLayout]);
} else {
    $url = Url::to(['product', 'id' =>$model->id]);
}

$balance = $model->getQuantity();
$countProductInOrder = $order->getCountProductInOrder($model->id);
$balanceForOrder = $balance;

?>

<div class="product-thumb">
    <?php if ($model->mainPhoto): ?>
        <div class="image">
            <a href="<?= Html::encode($url) ?>">
                <img src="<?= Html::encode($model->mainPhoto->getThumbFileUrl('file', 'catalog_list')) ?>" alt="" class="img-responsive" />
            </a>
        </div>
    <?php endif; ?>
    <div class="caption">
        <h4><a href="<?= Html::encode($url) ?>"><?= Html::encode($model->name) ?></a></h4>
        <small><b>Аренда:</b></small><div class="price"><?=Html::encode($model->priceRent_text)?></div>
        <small><b>Продажа:</b></small><div class="price"><?=Html::encode($model->priceSale_text)?></div>
        <label>На сайте</label>
        <?php
        if ($model->isOnSite()) {
            echo Html::checkbox('on_site',true,['class'=>'chk_on_site','data-url'=>Url::toRoute(['on-site','product_id'=>$model->id]), 'data-method'=>'POST', 'value'=>$model->on_site]);
        } else {
            echo Html::checkbox('on_site',false,['class'=>'chk_on_site','data-url'=>Url::toRoute(['on-site','product_id'=>$model->id]), 'data-method'=>'POST', 'value'=>$model->on_site]);
        }
        ?>

        <!--            <div class="description-small">--><?//= $model->shortDescription?><!--</div>-->
<!--        <div class="description-small"><small>Доступно для заказа:</small> <br>--><?//=$balanceForOrder?><!-- шт. </div>-->
        <div class="description-small"><small>На складе:</small> <br><?=$balance?>  шт. </div>
        <div class="description-small"><small>Уже в смете:</small> <br><?=$countProductInOrder?>  шт. </div>
        <?= $model->canRent() ? Html::a('Арендовать', ['shop/order/item-add-ajax', 'product_id' => $model->id,'type_id'=>OrderItem::TYPE_RENT], ['class' => 'btn btn-info add2order', 'data-method' => 'post','data-qty' => $balance]):null ?>
        <?= $model->canSale() ? Html::a('Купить', ['shop/order/item-add-ajax', 'product_id' => $model->id,'type_id'=>OrderItem::TYPE_SALE], ['class' => 'btn btn-warning add2order', 'data-method' => 'post', 'data-qty' => $balance]):null ?>
    </div>
    <div class="clear"></div>
</div>
