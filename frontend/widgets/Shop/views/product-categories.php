<?php

use rent\entities\Shop\Order\Item\OrderItem;
use yii\helpers\Url;
use \yii\helpers\Html;

/** @var \rent\entities\Shop\Category $category */
/** @var \rent\entities\Shop\Product\Product $product */

$rand=rand();
$countCategory=count($category->children);
$allCategories=array_merge([$category],$category->children);
?>
<section class="htc__product__area bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <!-- @chalma - пункты меню слева-->
                <div class="product-categories-all">
                    <?php if ($category->children) :?>
                    <div class="product-categories-menu">
                        <ul class="tab-style owl-filter-left_<?=$rand?>" role="tablist">
                            <li class="active" >
                                <div class="product-categories-title">
                                    <h3><a href="#" class="item" data-owl-filter="*" ><?=Html::encode($category->name)?></a></h3>
                                </div>
                            </li>
                            <?php foreach ($category->children as $child) : ?>
                                <li><a href="#" class=" item cat-id_<?=$child->id?>" data-owl-filter=".cat-id_<?=$child->id?>"><?=$child->name?></a></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="col-md-9  col-sm-9">
                <!-- @chalma - вывод товаров.-->
                <div class="tab-content another-product-style jump" >
                    <?php
                        $active='active';
                        $i=0;
                    ?>
                    <div class="tab-pane <?=$active?>" id="tab-pane_<?=$rand?>">
                        <div class="product-tab-list">
                            <div class="filter-menu owl-filter-top_<?=$rand?>">
                                <ul>
                                    <li class="filter-btn_all btn-active item" data-owl-filter="*">Все</li>
                                    <li class="filter-btn_pokupka item" data-owl-filter=".pokupka">ПОКУПКА</li>
                                    <li class="filter-btn_arenda item" data-owl-filter=".arenda">АРЕНДА</li>
                                    <li class="filter-btn_v-nalicii item" data-owl-filter=".v-nalicii">В НАЛИЧИИ</li>
                                </ul>
                            </div>
                        </div>
                        <div class="filter-item filter-item_<?=$rand?> owl-carousel">
                            <!--@chalma - вывод товаров из основной категории-->
                            <?php foreach ($category->getProducts() as $product) : ?>
                                <?php
                                $url = Url::to(['shop/catalog/product', 'id' =>$product->id]);
                                $classes = 'cat-id_'.$product->category_id;
                                if ($product->canRent()) $classes .= " arenda arenda-cat-id_".$product->category_id;
                                if ($product->canSale()) $classes .= " pokupka pokupka-cat-id_".$product->category_id;
                                if ($product->inStock()) $classes .= " v-nalicii v-nalicii-cat-id_".$product->category_id;
                                ?>
                                <div class="item item_<?=$rand?> <?=$classes?>">
                                    <div class="product">
                                        <div class="product__inner">
                                            <div class="pro__thumb">
                                                <a href="<?=$url?>">
                                                    <img src="<?=$product->mainPhoto->getThumbFileUrl('file','166x166')?>" width="100%">
                                                </a>
                                            </div>
                                            <div class="product__hover__info">
                                                <ul class="product__action">
<!--                                                    <li><a data-toggle="modal" data-target="#productModal" title="Quick View" class="quick-view modal-view detail-link" href="#"><span class="ti-plus"></span></a></li>-->
                                                    <?php if ($product->canRent()) :?>
                                                        <li><a title="Аренда" class="btn-add-ajax" href="<?= Url::to(['/shop/cart/add-ajax', 'id' => $product->id,'type'=>OrderItem::TYPE_RENT]) ?>" ><span class="ti-reload"></span></a></li>
                                                    <?php endif;?>
                                                    <?php if ($product->canSale()) :?>
                                                        <li><a title="Купить" class="btn-add-ajax" href="<?= Url::to(['/shop/cart/add-ajax', 'id' => $product->id,'type'=>OrderItem::TYPE_SALE]) ?>" ><span class="ti-shopping-cart"></span></a></li>
                                                    <?php endif;?>
                                                    <li><a title="В избранное" class="btn-add-ajax" href="<?= Url::to(['/cabinet/wishlist/add', 'id' => $product->id]) ?>" ><span class="ti-heart"></span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product__details">
                                            <h3><a href="<?=$url?>"><?=$product->name?></a></h3>
                                            <ul class="product__price">
                                                <?php if ($product->priceRent) :?>
                                                    <li>
                                                        <span style="color: #000">Аренда -</span> <?=$product->priceRent;?> р.
                                                    </li>
                                                <?php endif;?>
                                                <?php if ($product->priceSale) :?>
                                                    <li>
                                                        <span style="color: #000">Продажа -</span> <?=$product->priceSale;?> р.
                                                    </li>
                                                <?php endif;?>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <?php
                    $active='';
                    $i++;
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

$js = <<<JS
jQuery(document).ready(function($){
    
    let owl = $('.filter-item_$rand').owlCarousel({
        loop:false,
        margin:10,
        nav:true,
        navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
    })
    $( '.owl-filter-left_$rand' ).on( 'click', '.item', function(e) {
        e.preventDefault();
        // console.log('click on left menu');
        let item = $(this);
        let filter = item.data( 'owl-filter' )
        
        item.closest('li').addClass( 'active' ).siblings().removeClass( 'active' );
        
        $("#tab-pane_$rand .filter-btn_all").data('owl-filter',filter);
        $("#tab-pane_$rand .filter-btn_all").addClass('btn-active').siblings().removeClass('btn-active');
        
        //для главной категории
        if (filter == '*') {
            // console.log(filter);
            $("#tab-pane_$rand .filter-btn_arenda").data('owl-filter','.arenda');
            $("#tab-pane_$rand .filter-btn_pokupka").data('owl-filter','.pokupka');
            $("#tab-pane_$rand .filter-btn_v-nalicii").data('owl-filter','.v-nalicii');
        } else {
            $("#tab-pane_$rand .filter-btn_arenda").data('owl-filter','.arenda-'+filter.slice(1));
            $("#tab-pane_$rand .filter-btn_pokupka").data('owl-filter','.pokupka-'+filter.slice(1));
            $("#tab-pane_$rand .filter-btn_v-nalicii").data('owl-filter','.v-nalicii-'+filter.slice(1));            
        }
        
 
        
        owl.owlcarousel2_filter( filter );
    } )
    $( '.owl-filter-top_$rand' ).on( 'click', '.item', function(e) {
        e.preventDefault();
        // console.log('click on top menu');
        let item = $(this);
        item.addClass( 'btn-active' ).siblings().removeClass( 'btn-active' );
        let filter = item.data( 'owl-filter' )
        owl.owlcarousel2_filter( filter );
    } )
});

JS;
$this->registerJs($js);
?>