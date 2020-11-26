<?php
use yii\helpers\Url;
use \yii\helpers\Html;

/** @var \rent\entities\Shop\Category $category */
//var_dump($category);exit;
/** @var \rent\entities\Shop\Product\Product $product */
//var_dump(Yii::$app->params['siteId']);
?>

<?php //foreach ($category->getProducts() as $product) : ?>
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
<?php //endforeach;?>


<section class="htc__product__area bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- @chalma - пункты меню слева-->
                <div class="product-categories-all">
                    <div class="product-categories-title">
                        <h3><?=Html::encode($category->name)?></h3>
                    </div>
                    <?php if ($category->children) :?>
                    <div class="product-categories-menu">
                        <ul class="tab-style" role="tablist">
                            <li class="active" style="height: 1px;width: 1px;">
                                <a href="#home1" data-toggle="tab" onclick="countRabbits(this.href)">
                                </a>
                            </li>
                            <?php $i=1; foreach ($category->children as $child) : ?>
                                <li><a href="#home<?=$i=$i+1?>" data-toggle="tab" ><?=$child->name?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="col-md-9">
                <!-- @chalma - вывод товаров.-->
                <div class="tab-content another-product-style jump" >
                    <div class="tab-pane active" id="home1">
                        <div class="product-tab-list">
                            <div class="filter-menu">
                                <ul>
                                    <li class="filter-btn btn-active" data-filter="*">Все</li>
                                    <li class="filter-btn" data-filter=".pokupka">ПОКУПКА</li>
                                    <li class="filter-btn" data-filter=".arenda">АРЕНДА</li>
                                    <li class="filter-btn" data-filter=".v-nalicii">В НАЛИЧИИ</li>
                                </ul>
                            </div>
                        </div>
                        <div class="filter-item">

                            <!--@chalma - вывод товаров из основной категории-->
                            <?php foreach ($category->getProducts() as $product) : ?>
                                <?php
                                $classes = '';
                                if ($product->canRent()) $classes .= " arenda";
                                if ($product->canSale()) $classes .= " pokupka";
                                if ($product->inStock()) $classes .= " v-nalicii";
                                ?>
                                <div class="item <?=$classes?>">
                                    <div class="product">
                                        <div class="product__inner">
                                            <div class="pro__thumb">
                                                <a href="#">
                                                    <!--                                                        <img src="images/product/1.png" alt="product images">-->
                                                    <img src="<?=$product->mainPhoto->getThumbFilePath('file','270x270')?>" width="100%">
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
                                            <h2><a href="product-details.html"><?=$product->name?></a></h2>
                                            <ul class="product__price">
                                                <li class="old__price">$16.00</li>
                                                <li class="new__price">$10.00</li>
                                            </ul>
                                        </div>
                                    </div>
<!--                                    <p>-->
<!--                                        --><?//=$product->name?>
<!--                                    </p>-->
<!--                                    <p>-->
<!--                                        <img src="--><?//=$product->mainPhoto->getThumbFilePath('file','270x270')?><!--" width="100%">-->
<!--                                    </p>-->
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
                        </div>
                    </div>
                    <!-- @chalma - Просматриваем подкатегориии основной категории -->
                    <?php $l=2;?>
                    <?php foreach ($category->children as $child) : ?>
                        <!-- @chalma - Кнопки фильтор для подкатегорий -->
                        <div class="tab-pane" id="home<?=$l?>">
                            <div class="product-tab-list">
                                <div class="filter-menu<?=$l?>">
                                    <ul>
                                        <li class="filter-btn<?=$l?> btn-active" data-filter="*">Все</li>
                                        <li class="filter-btn<?=$l?>" data-filter=".pokupka">ПОКУПКА</li>
                                        <li class="filter-btn<?=$l?>" data-filter=".arenda">АРЕНДА</li>
                                        <li class="filter-btn<?=$l?>" data-filter=".v-nalicii">В НАЛИЧИИ</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="filter-item<?=$l?>">
                                <!-- @chalma - выводим товары из подкатегории-->
                                <?php foreach ($child->getProducts() as $product) : ?>
                                    <?php
                                    $classes = '';
                                    if ($product->canRent()) $classes .= " arenda";
                                    if ($product->canSale()) $classes .= " pokupka";
                                    if ($product->inStock()) $classes .= " v-nalicii";
                                    ?>
                                    <div class="item <?=$classes?>">
                                        <div class="product">
                                            <div class="product__inner">
                                                <div class="pro__thumb">
                                                    <a href="#">
<!--                                                        <img src="images/product/1.png" alt="product images">-->
                                                        <img src="<?=$product->mainPhoto->getThumbFilePath('file','270x270')?>" width="100%">
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
                                                <h2><a href="product-details.html"><?=$product->name?></a></h2>
                                                <ul class="product__price">
                                                    <li class="old__price">$16.00</li>
                                                    <li class="new__price">$10.00</li>
                                                </ul>
                                            </div>
                                        </div>
<!--                                        <p>-->
<!--                                            --><?//=$product->name?>
<!--                                        </p>-->
<!--                                        <p>-->
<!--                                            <img src="--><?//=$product->mainPhoto->getThumbFilePath('file','270x270')?><!--" width="100%">-->
<!--                                        </p>-->
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
                            </div>
                        </div>
                        <?php $l=$l+1;?>
                    <?php endforeach;?>
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