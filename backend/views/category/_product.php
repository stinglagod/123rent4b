<?php
use yii\helpers\Html;
use common\models\Image;


/** @var $model \common\models\Product */
/** @var $productGroup \common\models\ProductCategory */
//$productGroup_id=isset($this->context->actionParams['id'])?$this->context->actionParams['id']:"";
//echo "<pre>"; print_r($this->context->actionParams['id']); echo "</pre>";
$currentOrder=\common\models\Order::getCurrent();
?>
    <div class="product-thumb">
        <div class="image">
            <?php
            $option=[
                'class' => 'img-responsive',
                'alt' => Html::encode($model->name),
            ];
            echo Html::a(Html::img($model->getThumb(), $option), "#" , array('class' => 'lazy lazy-loaded viewProduct'));
            ?>
        </div>
        <div class="caption">
            <div class="name"><?= Html::a(Html::encode($model->name), "#", array('class' => 'viewProduct') );?></div>
            <small><b>Аренда:</b></small><div class="price"><?=$model->priceRent?$model->priceRent.' руб/сут':"Под заказ"?></div>
            <small><b>Продажа:</b></small><div class="price"><?=$model->priceSelling?$model->priceSelling.' руб':"Под заказ"?></div>

<!--            <div class="description-small">--><?//= $model->shortDescription?><!--</div>-->
            <div class="description-small"><small>В наличии на <?=date("d.m.Y", strtotime($currentOrder->dateBegin))?>:</small> <br><?=$model->getBalance($currentOrder->dateBegin)?>  шт. </div>
            <div class="description"><?=$model->shortDescription?></div>
            <div class="cart-button">
                <?php
                    echo Html::beginTag('button', array('class' => 'btn btn-block btn-success addToBasket', 'data-id'=>$model->id,'type'=>'button', 'data-toggle'=>'tooltip'));
                echo    Html::tag('i', '', array('class' => 'fa fa-cart-plus'));
                echo    Html::tag('span',Yii::t('app', ' Сдача в аренду'));
                echo Html::endTag('button');
                ?>
            </div>
            <div class="cart-button">
                <?php
                echo Html::beginTag('button', array('class' => 'btn btn-block btn-warning addToBasket', 'data-id'=>$model->id,'type'=>'button', 'data-toggle'=>'tooltip'));
                echo    Html::tag('i', '', array('class' => 'fa fa-cart-plus'));
                echo    Html::tag('span',Yii::t('app', ' Продажа'));
                echo Html::endTag('button');
                ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>

