<?php
use yii\helpers\Url;
use \yii\helpers\Html;

/** @var \rent\entities\Shop\Category $category */
//var_dump($category);exit;
/** @var \rent\entities\Shop\Product\Product $product */
//var_dump(Yii::$app->params['siteId']);
?>
77777
<?php foreach ($category->getProducts() as $product) : ?>
<!--    <div class="row">-->
<!--        <div class="col-md-2">-->
<!--            --><?//=$product->name?>
<!--        </div>-->
<!--        <div class="col-md-2">-->
<!--            --><?//=$product->mainPhoto->getThumbFilePath('file','270x270')?>
<!--        </div>-->
<!--        <div class="col-md-2">-->
<!--            --><?//=$product->canRent()?>
<!--        </div>-->
<!--        <div class="col-md-2">-->
<!--            --><?//=$product->canSale()?>
<!--        </div>-->
<!--        <div class="col-md-2">-->
<!--            --><?//=$product->inStock()?>
<!--        </div>-->
<!---->
<!--    </div>-->
<?php endforeach;?>
88888888888888888888888
<?php /** @var \rent\entities\Shop\Category $child */?>
<?php foreach ($category->children as $child) : ?>
    <p><?=$child->name?></p>
    <p><?=$child->id?></p>
    <?php foreach ($child->getProducts() as $product) : ?>
        <label><?=$product->id?></label>
    <?php endforeach;?>
<?php endforeach;?>
99999999999999999999999999
<!-- Основная категория-->
<p><?=$category->name?></p>
<p><?=$category->id?></p>
<!-- выводим товары из основной категориии-->
<?php foreach ($category->getProducts() as $product) : ?>
    <label><?=$product->id?></label>
<?php endforeach;?>
<?php /** @var \rent\entities\Shop\Category $child */?>
<!-- Просматриваем подкатегориии основной категории -->
<?php foreach ($category->children as $child) : ?>
    <!-- Подкатегория -->
    <p><?=$child->name?></p>
    <p><?=$child->id?></p>
    <!-- выводим товары из подкатегории-->
    <?php foreach ($child->getProducts() as $product) : ?>
        <label><?=$product->id?></label>
    <?php endforeach;?>
<?php endforeach;?>
<section class="htc__product__area bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="product-categories-all">
                    <div class="product-categories-title">
                        <h3><?=Html::encode($category->name)?></h3>
                    </div>
                    <?php if ($category->children) :?>
                    <div class="product-categories-menu">
<!--                        <ul>-->
<!--                            --><?php //foreach ($category->children as $child) : ?>
<!--                                <li><a href="--><?//=Url::toRoute(['/shop/catalog/category', 'id' => $child->id])?><!--">--><?//=$child->name?><!--</a></li>-->
<!--                            --><?php //endforeach;?>
<!--                        </ul>-->
                        <!--13.11.2020-chalma новое меню слева для товаров-->
                        <div class="filter-menu">
                            <ul>
                                <?php foreach ($category->children as $child) : ?>
<!--                                    <li><a href="--><?//=Url::toRoute(['/shop/catalog/category', 'id' => $child->id])?><!--">--><?//=$child->name?><!--</a></li>-->

                                    <!--13.11.2020-chalma элементы меню слева для товаров-->
                                    <li class="filter-btn" data-filter="<?=$child->id?>"><?=$child->name?></li><br>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="col-md-9">
                <div class="product-style-tab">
                    <div class="product-tab-list">
                        <!-- Nav tabs
                        <ul class="tab-style" role="tablist">
                            <li class="active">
                                <a href="#home1" data-toggle="tab">
                                    <div class="tab-menu-text">
                                        <h4> Все </h4>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#home2" data-toggle="tab">
                                    <div class="tab-menu-text">
                                        <h4>Покупка </h4>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#home2" data-toggle="tab">
                                    <div class="tab-menu-text">
                                        <h4>Аренда </h4>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#home3" data-toggle="tab">
                                    <div class="tab-menu-text">
                                        <h4>В наличии</h4>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        -->
                        <!--13.11.2020-chalma новые значения фильтров-->
                        <div class="filter-menu">
                            <ul>
                                <li class="filter-btn btn-active" data-filter="*">Все</li>
                                <li class="filter-btn" data-filter=".pokupka">ПОКУПКА</li>
                                <li class="filter-btn" data-filter=".arenda">АРЕНДА</li>
                                <li class="filter-btn" data-filter=".v-nalicii">В НАЛИЧИИ</li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content another-product-style jump">
                        <div class="filter-item">

                            <!-- выводим товары из основной категориии-->
                            <?php foreach ($category->getProducts() as $product) : ?>
                                <?php
                                $classes = '';
                                if ($product->canRent()) $classes .= ' arenda';
                                if ($product->canSale()) $classes .= ' pokupka';
                                if ($product->inStock()) $classes .= ' v-nalicii';
                                ?>
                                <div class="item <?=$category->id ?><?=$classes?>">
                                    <p>
                                        <?=$category->id ?>
                                        <?=$product->name?>
                                    </p>
                                    <p>
                                        <img src="<?=$product->mainPhoto->getThumbFilePath('file','270x270')?>" width="100%">
                                    </p>
                                    <p>
                                        Aренда - <?=$product->canRent()?>
                                    </p>
                                    <p>
                                        Продажа - <?=$product->canSale()?>
                                    </p>
                                    <p>
                                        В Наличии - <?=$product->inStock()?>
                                    </p>

                                </div>
                            <?php endforeach;?>
                            <?php /** @var \rent\entities\Shop\Category $child */?>
                            <!-- Просматриваем подкатегориии основной категории -->
                            <?php foreach ($category->children as $child) : ?>
                                <!-- выводим товары из подкатегории-->
                                <?php foreach ($child->getProducts() as $product) : ?>
                                    <?php
                                    $classes = '';
                                    if ($product->canRent()) $classes .= ' arenda';
                                    if ($product->canSale()) $classes .= ' pokupka';
                                    if ($product->inStock()) $classes .= ' v-nalicii';
                                    ?>
                                    <div class="item <?=$child->id ?><?=$classes?>">
                                        <p>
                                            <?=$child->id ?><br>
                                            <?=$product->name?>
                                        </p>
                                        <p>
                                            <img src="<?=$product->mainPhoto->getThumbFilePath('file','270x270')?>" width="100%">
                                        </p>
                                        <p>
                                            Aренда - <?=$product->canRent()?>
                                        </p>
                                        <p>
                                            Продажа - <?=$product->canSale()?>
                                        </p>
                                        <p>
                                            В Наличии - <?=$product->inStock()?>
                                        </p>

                                    </div>
                                <?php endforeach;?>
                            <?php endforeach;?>






<!--                            <div class="item pokupka"><h4>ПОКУПКА</h4></div>-->
<!--                            <div class="item arenda"><h4>АРЕНДА</h4></div>-->
<!--                            <div class="item pokupka"><h4>ПОКУПКА</h4></div>-->
<!--                            <div class="item v-nalicii"><h4>В НАЛИЧИИ</h4></div>-->

                        </div>

                        <!--13.11.2020 @chalama - старый вывод товаров ниже-->
                        <!--
                        <div class="tab-pane active" id="home1">
                            <div class="row">
                                <div class="product-slider-active owl-carousel">
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/1.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/2.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/3.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/4.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/5.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="home2">
                            <div class="row">
                                <div class="product-slider-active owl-carousel">
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/4.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/5.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/6.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/7.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/8.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="home3">
                            <div class="row">
                                <div class="product-slider-active owl-carousel">
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/2.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/1.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/5.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/4.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/3.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="home4">
                            <div class="row">
                                <div class="product-slider-active owl-carousel">
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/9.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/5.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/3.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/4.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 single__pro col-lg-4 cat--1 col-sm-4 col-xs-12">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
                                                        <img src="images/product/2.png" alt="product images">
                                                    </a>
                                                </div>
                                                <div class="product__hover__info">
                                                    <ul class="product__action">
                                                        <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>
                                                        <li><a title="Add TO Cart" href="cart.html"><span class="ti-shopping-cart"></span></a></li>
                                                        <li><a title="Wishlist" href="wishlist.html"><span class="ti-heart"></span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="product__details">
                                                <h2><a href="product-details.html">Simple Black Clock</a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                        <!--13.11.2020 @chalama - старый вывод товаров ниже-->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="owl-filter">
        <div class="container">
            <div class="row">
                <div class="col-md-12">


                </div>
            </div>
        </div>
    </div>
</section>

<?php

$js = <<<JS
console.log('tut js code');


JS;
$this->registerJs($js);
?>

