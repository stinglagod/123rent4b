<?php

/* @var $this yii\web\View */
/* @var $model rent\entities\Shop\Product\Product */

use rent\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$url = Url::to(['product', 'id' =>$model->id]);
$balance = $model->getQuantity();
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

        <!--            <div class="description-small">--><?//= $model->shortDescription?><!--</div>-->
        <div class="description-small"><small>Доступно для заказа:</small> <br><?=$balanceForOrder?> шт. </div>
        <div class="description-small"><small>Всего в наличии на складе:</small> <br><?=$balance?>  шт. </div>
    </div>
    <div class="clear"></div>
</div>
