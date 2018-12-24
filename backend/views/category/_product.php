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
            <div class="price">
                <?=$model->cost?>
            </div>
            <div class="name"><?= Html::a(Html::encode($model->name), "#", array('class' => 'viewProduct') );?></div>
            <div class="description-small"><?= $model->shortDescription?></div>
            <div class="description">В наличии на '<?=date("d.m.Y", strtotime($currentOrder->dateBegin))?>: <?=$model->getBalance($currentOrder->dateBegin)?>  шт. </div>
            <div class="description"><?=$model->shortDescription?></div>
            <div class="cart-button">
                <?php
//                $unit=$model->getProductUnit()['unit']?$model->getProductUnit()['unit']:1;
                    echo Html::beginTag('button', array('class' => 'btn btn-block btn-success addToBasket', 'data-id'=>$model->id,'type'=>'button', 'data-toggle'=>'tooltip'));
                echo    Html::tag('i', '', array('class' => 'fa fa-cart-plus'));
                echo    Html::tag('span',Yii::t('app', ' Добавить в заказ'));
                echo Html::endTag('button');
                ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>

