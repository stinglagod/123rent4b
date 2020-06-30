<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\datecontrol\DateControlAsset;

AppAsset::register($this);
\frontend\assets\SlickCarouselAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Body main wrapper start -->
<div class="wrapper fixed__footer">
    <!-- Start Header Style -->
    <header id="header" class="htc-header header--3 bg__white">
        <!-- Start Mainmenu Area -->
        <div id="sticky-header-with-topbar" class="mainmenu__area sticky__header">
            <div class="container">
                <div class="row">
                    <div class="col-md-2 col-lg-2 col-sm-3 col-xs-3">
                        <div class="logo">
                            <a href="<?=Url::toRoute(["/"])?>">
                                <img src="/images/logo/logo.png" alt="logo">
                            </a>
                            <?=Yii::$app->params['siteDomain']?>
                        </div>

                    </div>
                    <!-- Start MAinmenu Ares -->
                    <div class="col-md-8 col-lg-8 col-sm-6 col-xs-6">
                        <nav class="mainmenu__nav hidden-xs hidden-sm">
                            <ul class="main__menu">
                                <li class="drop"><a href="<?=Url::toRoute(["/"])?>"><b>Главная</b></a>
                                    <ul class="dropdown">
                                        <li><a href="index.html">Home 1</a></li>
                                        <li><a href="index-2.html">Home 2</a></li>
                                    </ul>
                                </li>
                                <li><a href="/catalog/"><b>Каталог</b></a></li>
                                <li class="drop"><a href="portfolio-gutter-box-3.html">Портфолио</a>
                                    <ul class="dropdown">
                                        <li><a href="portfolio-gutter-box-3.html">Boxed Gutter 3 Col</a></li>
                                        <li><a href="portfolio-gutter-full-wide-4.html">Wide Gutter 4 Col </a></li>
                                        <li><a href="portfolio-card-box-3.html">Card Gutter 3 Col </a></li>
                                        <li><a href="portfolio-masonry-3.html">Masonry Box 3 Col </a></li>
                                        <li><a href="portfolio-gutter-masonry-fullwide-4.html">Masonry Wide 4 Col </a></li>
                                        <li><a href="portfolio-gutter-box-3-carousel.html">carousel Gutter 3 Col </a></li>
                                        <li><a href="portfolio-justified-box-3.html">justified box 3 Col</a></li>
                                        <li><a href="single-portfolio-gallery.html">Portfolio Details </a></li>
                                    </ul>
                                </li>
                                <li class="drop"><a href="blog.html">Blog</a>
                                    <ul class="dropdown">
                                        <li><a href="blog.html">blog 3 column</a></li>
                                        <li><a href="blog-2-col-rightsidebar.html">2 col right siderbar</a></li>
                                        <li><a href="blog-details-left-sidebar.html"> blog details</a></li>
                                    </ul>
                                </li>
                                <li class="drop"><a href="shop.html">Shop</a>
                                    <ul class="dropdown mega_dropdown">
                                        <!-- Start Single Mega MEnu -->
                                        <li><a class="mega__title" href="shop.html">shop layout</a>
                                            <ul class="mega__item">
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                            </ul>
                                        </li>
                                        <!-- End Single Mega MEnu -->
                                        <!-- Start Single Mega MEnu -->
                                        <li><a class="mega__title" href="shop.html">product details layout</a>
                                            <ul class="mega__item">
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                                <li><a href="#">demo page title</a></li>
                                            </ul>
                                        </li>
                                        <!-- End Single Mega MEnu -->
                                        <!-- Start Single Mega MEnu -->
                                        <li>
                                            <ul class="mega__item">
                                                <li>
                                                    <div class="mega-item-img">
                                                        <a href="shop.html">
                                                            <img src="images/feature-img/3.png" alt="">
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <!-- End Single Mega MEnu -->
                                    </ul>
                                </li>
                                <li class="drop"><a href="#">pages</a>
                                    <ul class="dropdown">
                                        <li><a href="about.html">about</a></li>
                                        <li><a href="shop.html">shop</a></li>
                                        <li><a href="shop-sidebar.html">shop sidebar</a></li>
                                        <li><a href="product-details-sticky-right.html">product details</a></li>
                                        <li><a href="cart.html">cart</a></li>
                                        <li><a href="wishlist.html">wishlist</a></li>
                                        <li><a href="checkout.html">checkout</a></li>
                                        <li><a href="team.html">team</a></li>
                                        <li><a href="login-register.html">login & register</a></li>
                                    </ul>
                                </li>
                                <li><a href="contact.html">Контакты</a></li>
                            </ul>
                        </nav>
                        <div class="mobile-menu clearfix visible-xs visible-sm">
                            <nav id="mobile_dropdown">
                                <ul>
                                    <li><a href="index.html">Home</a>
                                        <ul>
                                            <li><a href="index.html">Home 1</a></li>
                                            <li><a href="index-2.html">Home 2</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">portfolio</a>
                                        <ul class="dropdown">
                                            <li><a href="portfolio-gutter-box-3.html">Boxed Gutter 3 Col</a></li>
                                            <li><a href="portfolio-gutter-full-wide-4.html">Wide Gutter 4 Col </a></li>
                                            <li><a href="portfolio-card-box-3.html">Card Gutter 3 Col </a></li>
                                            <li><a href="portfolio-masonry-3.html">Masonry Box 3 Col </a></li>
                                            <li><a href="portfolio-gutter-masonry-fullwide-4.html">Masonry Wide 4 Col </a></li>
                                            <li><a href="portfolio-gutter-box-3-carousel.html">carousel Gutter 3 Col </a></li>
                                            <li><a href="portfolio-justified-box-3.html">justified box 3 Col</a></li>
                                            <li><a href="single-portfolio-gallery.html">Portfolio Details </a></li>
                                        </ul>
                                    </li>
                                    <li><a href="blog.html">blog</a>
                                        <ul>
                                            <li><a href="blog.html">blog 3 column</a></li>
                                            <li><a href="blog-2-col-rightsidebar.html">2 col right siderbar</a></li>
                                            <li><a href="blog-details-left-sidebar.html"> blog details</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#">pages</a>
                                        <ul>
                                            <li><a href="about.html">about</a></li>
                                            <li><a href="shop.html">shop</a></li>
                                            <li><a href="shop-sidebar.html">shop sidebar</a></li>
                                            <li><a href="product-details-sticky-right.html">product details</a></li>
                                            <li><a href="cart.html">cart</a></li>
                                            <li><a href="wishlist.html">wishlist</a></li>
                                            <li><a href="checkout.html">checkout</a></li>
                                            <li><a href="team.html">team</a></li>
                                            <li><a href="login-register.html">login & register</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="contact.html">Контакты</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- End MAinmenu Ares -->
                    <div class="col-md-2 col-sm-4 col-xs-3">
                        <ul class="menu-extra">
                            <li class="search search__open hidden-xs"><span class="ti-search"></span></li>
                            <li>
                                <?php if (Yii::$app->user->isGuest) { ?>
                                    <a href="<?=Url::toRoute(["site/login"])?>" title="Войти" ><span class="ti-user"></span></a>
                                <?php } else { ?>
                                    <a href="<?=Url::toRoute(["site/lk"])?>" title="Выйти"><span class="ti-settings"></span></a>
                                <?php }?>
                            </li>
                            <li class="cart__menu"><span class="ti-shopping-cart"></span></li>
                        </ul>
                    </div>
                </div>
                <div class="mobile-menu-area"></div>
            </div>
        </div>
        <!-- End Mainmenu Area -->
    </header>
    <!-- End Header Style -->

    <div class="body__overlay"></div>
    <!-- Start Offset Wrapper -->
    <div class="offset__wrapper">
        <!-- Start Search Popap -->
        <div class="search__area">
            <div class="container" >
                <div class="row" >
                    <div class="col-md-12" >
                        <div class="search__inner">
                            <form action="#" method="get">
                                <input placeholder="Поиск здесь... " type="text">
                                <button type="submit"></button>
                            </form>
                            <div class="search__close__btn">
                                <span class="search__close__btn_icon"><i class="zmdi zmdi-close"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Search Popap -->
        <!-- Start Cart Panel -->
        <?php
        /** @var \common\models\Order $order */
        if ($order=\common\models\Order::getActual()) {
            echo $this->render('../order/cart/_cartPanel',
                [
                    'order' => $order,
                ]);
        } else {
            echo $this->render('../order/cart/_cartPanelBlank');
        }

        ?>
        <!-- End Cart Panel -->
    </div>
    <!-- End Offset Wrapper -->
    <!-- Start Bradcaump area -->
    <?php if (empty($this->params['mainPage'])) { ?>
    <div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(/images/bg/2.jpg) no-repeat scroll center center / cover ;">
        <div class="ht__bradcaump__wrap">
            <div class="container">
                <div class="row">
                    <div class="col-xs-4">
                    </div>
                    <div class="col-xs-8">
                        <div class="bradcaump__inner text-right">
                            <?=
                            Breadcrumbs::widget([
                                'options'       =>  [
                                    'id'        =>  'breadCrumbs',
                                    'class'         =>  "bradcaump-inner"
                                ],
                                // settings of home link and display
                                'homeLink'      =>  [
                                    'label'     =>  Yii::t('yii', 'Home'),
                                    'url'       =>  ['/site/index'],
                                    'class'     =>  'breadcrumb-item',
                                    'template'  =>  '{link}',
                                ],
                                'links'         =>  $this->params['breadcrumbs'],
                                'itemTemplate'  =>  '<span class="brd-separetor">/</span><span class="breadcrumb-item active">{link}</span>',
                                'tag'           =>  'nav',
                                'activeItemTemplate' => '<span class="brd-separetor">/</span><span class="breadcrumb-item active">{link}</span>',

                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Bradcaump area -->
    <?php } ?>
    <div class="container">
        <?php Pjax::begin(['id' => 'pjax_alerts']) ?>
        <?= Alert::widget() ?>
        <?php Pjax::end() ?>
    </div>
    <?= $content ?>

    <!-- Start Footer Area -->
    <footer class="htc__foooter__area gray-bg">
        <div class="container">
            <div class="row">
                <div class="footer__container clearfix">
                    <!-- Start Single Footer Widget -->
                    <div class="col-md-3 col-lg-3 col-sm-6">
                        <div class="ft__widget">
                            <div class="ft__logo">
                                <a href="index.html">
                                    <img src="images/logo/logo.png" alt="footer logo">
                                </a>
                            </div>
                            <div class="footer-address">
                                <ul>
                                    <li>
                                        <div class="address-icon">
                                            <i class="zmdi zmdi-pin"></i>
                                        </div>
                                        <div class="address-text">
                                            <p>194 Main Rd T, FS Rayed <br> VIC 3057, USA</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="address-icon">
                                            <i class="zmdi zmdi-email"></i>
                                        </div>
                                        <div class="address-text">
                                            <a href="#"> info@example.com</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="address-icon">
                                            <i class="zmdi zmdi-phone-in-talk"></i>
                                        </div>
                                        <div class="address-text">
                                            <p>+012 345 678 102 </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <ul class="social__icon">
                                <li><a href="#"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a href="#"><i class="zmdi zmdi-instagram"></i></a></li>
                                <li><a href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a href="#"><i class="zmdi zmdi-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Footer Widget -->
                    <!-- Start Single Footer Widget -->
                    <div class="col-md-3 col-lg-2 col-sm-6 smt-30 xmt-30">
                        <div class="ft__widget">
                            <h2 class="ft__title">Categories</h2>
                            <ul class="footer-categories">
                                <li><a href="shop-sidebar.html">Men</a></li>
                                <li><a href="shop-sidebar.html">Women</a></li>
                                <li><a href="shop-sidebar.html">Accessories</a></li>
                                <li><a href="shop-sidebar.html">Shoes</a></li>
                                <li><a href="shop-sidebar.html">Dress</a></li>
                                <li><a href="shop-sidebar.html">Denim</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Start Single Footer Widget -->
                    <div class="col-md-3 col-lg-2 col-sm-6 smt-30 xmt-30">
                        <div class="ft__widget">
                            <h2 class="ft__title">Infomation</h2>
                            <ul class="footer-categories">
                                <li><a href="about.html">About Us</a></li>
                                <li><a href="contact.html">Contact Us</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Returns & Exchanges</a></li>
                                <li><a href="#">Shipping & Delivery</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Start Single Footer Widget -->
                    <div class="col-md-3 col-lg-3 col-lg-offset-1 col-sm-6 smt-30 xmt-30">
                        <div class="ft__widget">
                            <h2 class="ft__title">Newsletter</h2>
                            <div class="newsletter__form">
                                <p>Subscribe to our newsletter and get 10% off your first purchase .</p>
                                <div class="input__box">
                                    <div id="mc_embed_signup">
                                        <form action="#" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                            <div id="mc_embed_signup_scroll" class="htc__news__inner">
                                                <div class="news__input">
                                                    <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Email Address" required>
                                                </div>
                                                <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_6bbb9b6f5827bd842d9640c82_05d85f18ef" tabindex="-1" value=""></div>
                                                <div class="clearfix subscribe__btn"><input type="submit" value="Send" name="subscribe" id="mc-embedded-subscribe" class="bst__btn btn--white__color">

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Footer Widget -->
                </div>
            </div>
            <!-- Start Copyright Area -->
            <div class="htc__copyright__area">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <div class="copyright__inner">
                            <div class="copyright">
                                <p>© 2020 <a href="#">RENT4B</a>
                                    All Right Reserved.</p>
                            </div>
                            <ul class="footer__menu">
                                <li><a href="index.html">Home</a></li>
                                <li><a href="shop.html">Product</a></li>
                                <li><a href="contact.html">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Copyright Area -->
        </div>
    </footer>
    <!-- End Footer Area -->
</div>
<!-- Body main wrapper end -->
<!-- QUICKVIEW PRODUCT -->
<div id="quickview-wrapper">
    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal__container" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="modal-product">
                        <!-- Start product images -->
                        <div class="product-images">
                            <div class="main-image images">
                                <img alt="big images" src="images/product/big-img/1.jpg">
                            </div>
                        </div>
                        <!-- end product images -->
                        <div class="product-info">
                            <h1>Simple Fabric Bags</h1>
                            <div class="rating__and__review">
                                <ul class="rating">
                                    <li><span class="ti-star"></span></li>
                                    <li><span class="ti-star"></span></li>
                                    <li><span class="ti-star"></span></li>
                                    <li><span class="ti-star"></span></li>
                                    <li><span class="ti-star"></span></li>
                                </ul>
                                <div class="review">
                                    <a href="#">4 customer reviews</a>
                                </div>
                            </div>
                            <div class="price-box-3">
                                <div class="s-price-box">
                                    <span class="new-price">$17.20</span>
                                    <span class="old-price">$45.00</span>
                                </div>
                            </div>
                            <div class="quick-desc">
                                Designed for simplicity and made from high quality materials. Its sleek geometry and material combinations creates a modern look.
                            </div>
                            <div class="select__color">
                                <h2>Select color</h2>
                                <ul class="color__list">
                                    <li class="red"><a title="Red" href="#">Red</a></li>
                                    <li class="gold"><a title="Gold" href="#">Gold</a></li>
                                    <li class="orange"><a title="Orange" href="#">Orange</a></li>
                                    <li class="orange"><a title="Orange" href="#">Orange</a></li>
                                </ul>
                            </div>
                            <div class="select__size">
                                <h2>Select size</h2>
                                <ul class="color__list">
                                    <li class="l__size"><a title="L" href="#">L</a></li>
                                    <li class="m__size"><a title="M" href="#">M</a></li>
                                    <li class="s__size"><a title="S" href="#">S</a></li>
                                    <li class="xl__size"><a title="XL" href="#">XL</a></li>
                                    <li class="xxl__size"><a title="XXL" href="#">XXL</a></li>
                                </ul>
                            </div>
                            <div class="social-sharing">
                                <div class="widget widget_socialsharing_widget">
                                    <h3 class="widget-title-modal">Share this product</h3>
                                    <ul class="social-icons">
                                        <li><a target="_blank" title="rss" href="#" class="rss social-icon"><i class="zmdi zmdi-rss"></i></a></li>
                                        <li><a target="_blank" title="Linkedin" href="#" class="linkedin social-icon"><i class="zmdi zmdi-linkedin"></i></a></li>
                                        <li><a target="_blank" title="Pinterest" href="#" class="pinterest social-icon"><i class="zmdi zmdi-pinterest"></i></a></li>
                                        <li><a target="_blank" title="Tumblr" href="#" class="tumblr social-icon"><i class="zmdi zmdi-tumblr"></i></a></li>
                                        <li><a target="_blank" title="Pinterest" href="#" class="pinterest social-icon"><i class="zmdi zmdi-pinterest"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="addtocart-btn">
                                <a href="#">Add to cart</a>
                            </div>
                        </div><!-- .product-info -->
                    </div><!-- .modal-product -->
                </div><!-- .modal-body -->
            </div><!-- .modal-content -->
        </div><!-- .modal-dialog -->
    </div>
    <!-- END Modal -->
</div>

<!--Общее модальное окно-->
<div id="modalBlock">

</div>

<!---->

<!--TODO сделать все граммотно-->
<!-- jquery latest version -->
<!--<script src="/js/vendor/jquery-1.12.0.min.js"></script>-->
<!-- Bootstrap framework js -->
<!--<script src="/js/bootstrap.min.js"></script>-->
<!-- All js plugins included in this file. -->
<!--<script src="/js/plugins.js"></script>-->
<!--<script src="/js/slick.min.js"></script>-->
<!--<script src="/js/owl.carousel.min.js"></script>-->
<!-- Waypoints.min.js. -->
<!--<script src="/js/waypoints.min.js"></script>-->
<!-- Main js file that contents all jQuery plugins activation. -->
<!--<script src="/js/main.js"></script>-->

<!--<script src="/js/site.js"></script>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
