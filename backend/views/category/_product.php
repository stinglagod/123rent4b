<?php
use yii\helpers\Html;
use common\models\Image;


/** @var $model \common\models\Product */
/** @var  \common\models\Category $category */
/* @var $orderblock_id integer */
/* @var $parent_id integer */
//$productGroup_id=isset($this->context->actionParams['id'])?$this->context->actionParams['id']:"";
$dateBegin=empty($this->context->actionParams['dateBegin'])?null:$this->context->actionParams['dateBegin'];
$dateEnd=empty($this->context->actionParams['dateEnd'])?null:$this->context->actionParams['dateEnd'];
$balance=$model->getBalance($dateBegin,$dateEnd);
$balanceSoft=$model->getBalance($dateBegin,$dateEnd);
$urlProduct=$model->getUrl($category?$category->alias:null);
?>
    <div class="product-thumb">
        <div class="image">
            <?php
            $option=[
                'class' => 'img-responsive',
                'alt' => Html::encode($model->name),
//                'height' =>\Yii::$app->params['thumbMiddleHeight'].'px',
//                'width' => \Yii::$app->params['thumbMiddleWidth'].'px',
//                'object-fit' => 'cover',
//                'dispaly'=>'block',
//            TODO: Сделать поэлегантнее
                'style' => 'object-fit: cover; height: '.\Yii::$app->params['thumbMiddleHeight'].'px'.'; width:'.\Yii::$app->params['thumbMiddleWidth'].'px'


            ];
            echo Html::a(Html::img($model->getThumb(), $option), $urlProduct,
                array(
                    'class' => 'lazy lazy-loaded viewProduct',
                    'data-url'=>$urlProduct,
                ));
            ?>
        </div>
        <div class="caption">
            <div class="name"><?= Html::a(Html::encode($model->name), $urlProduct, array('class' => 'viewProduct','data-url'=>$urlProduct,) );?></div>
            <small><b>Аренда:</b></small><div class="price"><?=$model->priceRent?$model->priceRent.' руб/сут':"Под заказ"?></div>
            <small><b>Продажа:</b></small><div class="price"><?=$model->priceSale?$model->priceSale.' руб':"Под заказ"?></div>

<!--            <div class="description-small">--><?//= $model->shortDescription?><!--</div>-->
            <div class="description-small"><small>Доступно для заказа:</small> <br><?=$balance?> (<?=$balanceSoft?>)шт. </div>
            <div class="description-small"><small>Всего в наличии на складе:</small> <br><?=$model->getBalanceStock()?>  шт. </div>
            <div class="description"><?=$model->shortDescription?></div>
            <?php if ($orderblock_id) { ?>
            <div class="cart-button">
                <?php
                    echo Html::beginTag('button', array('class' => 'btn btn-block btn-success addToBasket',
                                                                'data-id'=>$model->id,
                                                                'data-pricerent'=>$model->priceRent,
                                                                'data-orderblock_id'=>$orderblock_id,
                                                                'data-parent_id'=>empty($parent_id)?'':$parent_id,
                                                                'data-balance'=>$balance,
                                                                'data-balancesoft'=>$balanceSoft,
                                                                'type'=>'button',
                                                                'data-toggle'=>'tooltip'));
                echo    Html::tag('i', '', array('class' => 'fa fa-cart-plus'));
                echo    Html::tag('span',Yii::t('app', ' Сдача в аренду'));
                echo Html::endTag('button');
                ?>
            </div>
            <div class="cart-button">
                <?php
                echo Html::beginTag('button', array('class' => 'btn btn-block btn-warning addToBasket',
                                                            'data-id'=>$model->id,
                                                            'data-pricesale'=>$model->priceSale,
                                                            'data-orderblock_id'=>$orderblock_id,
                                                            'data-parent_id'=>empty($parent_id)?'':$parent_id,
                                                            'data-balance'=>$balance,
                                                            'data-balancesoft'=>$balanceSoft,
                                                            'type'=>'button',
                                                            'data-toggle'=>'tooltip'));
                echo    Html::tag('i', '', array('class' => 'fa fa-cart-plus'));
                echo    Html::tag('span',Yii::t('app', ' Продажа'));
                echo Html::endTag('button');
                ?>
            </div>
            <?php } ?>
        </div>
        <div class="clear"></div>
    </div>

