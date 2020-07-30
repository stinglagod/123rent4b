<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 18.09.2019
 * Time: 15:18
 */
use yii\widgets\ActiveForm;
use \kartik\widgets\TouchSpin;
use \common\models\OrderProduct;

/** @var $model \common\models\Product */
/** @var $image \common\models\File */
/** @var $category \common\models\Category */
/** @var $order \common\models\Order */
$images=$model->getFiles(\common\models\File::IMAGE);
$this->title = $model->name;
$this->params['breadcrumbs'][] = $category->name;
$this->params['breadcrumbs'][] = $this->title;
$dateBegin=$order?$order->dateBegin:null
?>

<section class="htc__product__details pt--40 pb--40 bg__white">
    <div class="container">
        <div class="scroll-active">
            <div class="row">
                <div class="col-md-7 col-lg-7 col-sm-5 col-xs-12">
                    <div class="product__details__container product-details-5">
                        <?php foreach ($images as $image) {?>
                        <div class="scroll-single-product mb--30">
                            <img src="<?=$image->getUrl()?>" alt="<?=$image->name?>">
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="sidebar-active col-md-5 col-lg-5 col-sm-7 col-xs-12 xmt-30">
                    <div class="htc__product__details__inner ">
                        <div class="pro__detl__title">
                            <h2><?=$model->name?></h2>
                        </div>
                        <div class="pro__dtl__rating">
                            <ul class="pro__rating">
                                <li><span class="ti-star"></span></li>
                                <li><span class="ti-star"></span></li>
                                <li><span class="ti-star"></span></li>
                                <li><span class="ti-star"></span></li>
                                <li><span class="ti-star"></span></li>
                            </ul>
                            <span class="rat__qun">(Based on 0 Ratings)</span>
                        </div>
                        <div class="product-action-wrap">
                            <div class="prodict-statas"><span>Всего на складе :</span></div>
                            <div class="product-quantity">
                                <?=$model->getBalance()?>
                            </div>
                        </div>
                        <div class="product-action-wrap">
                            <div class="prodict-statas"><span>Доступно на <?=$dateBegin?date('d.m.Y',strtotime($dateBegin)):date('d.m.Y')?> :</span></div>
                            <div class="product-quantity">
                                <?=$model->getBalance($dateBegin)?>
                            </div>
                        </div>
                        <div class="pro__details">
                            <p><?=$model->description?></p>
                        </div>
                        <?php if ($model->priceRent) { ?>
                            <ul class="pro__dtl__prize">
                                <li><?=$model->priceRent?> руб./сутки</li>
                            </ul>
                        <?php } ?>
                        <?php if ($model->priceSale) { ?>
                            <ul class="pro__dtl__prize">
                                <li><?=$model->priceSale?> руб.</li>
                            </ul>
                        <?php } ?>
                        <div class="product-action-wrap">
                            <?=
                            TouchSpin::widget([
                                'name' => 'Количество',
                                'value'=>1,
                                'options' => [
                                    'placeholder' => 'Количество ...',
                                    'pluginOptions' => ['min' => 0, 'max' => 5000]
                                ],
                                'pluginEvents' => [
                                    "change" => "function() { 
                                        qty=this.value;
                                        $.each($('.addToBasket'),function(index,value){
                                            value.dataset.qty=qty;
                                        }); 
                                    }",
                                ]
                            ]);
                            ?>
                        </div>
                        <ul class="pro__dtl__btn">
                            <?php if ($model->priceRent) { ?>
                                <li class="buy__now__btn"><a href="#" class="addToBasket" data-type='<?=OrderProduct::RENT?>' data-order_id='<?=$order->id?>' data-qty=1 data-product_id="<?=$model->id?>">Аренедовать</a></li>
                            <?php } ?>
                            <?php if ($model->priceSale) { ?>
                                <li class="buy__now__btn"><a href="#" class="addToBasket" data-type='<?=OrderProduct::SALE?>' data-order_id='<?=$order->id?>' data-qty=1 data-product_id="<?=$model->id?>">Купить</a></li>
                            <?php } ?>
                            <li><a href="#"><span class="ti-heart"></span></a></li>
                            <li><a href="#"><span class="ti-email"></span></a></li>
                        </ul>
                        <div class="pro__social__share">
                            <h2>Share :</h2>
                            <ul class="pro__soaial__link">
                                <li><a href="#"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="#"><i class="zmdi zmdi-instagram"></i></a></li>
                                <li><a href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="#"><i class="zmdi zmdi-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Product Details -->
<!-- Start Product tab -->
<section class="htc__product__details__tab bg__white pb--120">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <ul class="product__deatils__tab mb--60" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#description" role="tab" data-toggle="tab">Описание</a>
                    </li>
                    <li role="presentation">
                        <a href="#sheet" role="tab" data-toggle="tab">Характеристики</a>
                    </li>
                    <li role="presentation">
                        <a href="#reviews" role="tab" data-toggle="tab">Отзывы</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="product__details__tab__content">
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="description" class="product__tab__content fade in active">
                        <div class="product__description__wrap">
                            <div class="product__desc">
                                <h2 class="title__6">Описание</h2>
                                <p><?=$model->description?></p>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Content -->
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="sheet" class="product__tab__content fade">
                        <div class="pro__feature">
                            <h2 class="title__6">Характеристики</h2>
                            <ul class="feature__list">
                            <?php
                            $productAttributes = $model->initProdAttributesById();
                            foreach ($productAttributes as $productAttribute) {
                                $attr=$productAttribute->prodAttribute->attr_name;
                            ?>
                                <li><a href="#"><i class="zmdi zmdi-play-circle"></i><?=$productAttribute->prodAttribute->name?>: <?=$model->$attr?> </li></a>
                            <?php
                            }
                            ?>
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Content -->
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="reviews" class="product__tab__content fade">
                        <div class="review__address__inner">
                            <p>Отзывов пока нет</p>
                        </div>
                    </div>
                    <!-- End Single Content -->
                </div>
            </div>
        </div>
    </div>
</section>


