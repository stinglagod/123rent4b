<?php
/* @var $this \yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
?>
<!-- Start Header Style -->
<header id="header" class="htc-header header--3 bg__white">
    <!-- Start Mainmenu Area -->
    <div id="sticky-header-with-topbar" class="mainmenu__area sticky__header">
        <div class="container">
            <div class="row">
                <div class="col-md-2 col-lg-2 col-sm-3 col-xs-3">
                    <div class="logo">
                        <a href="<?=Url::toRoute(["/"])?>">
<!--                            <img src="/uploads/sites/--><?//=Html::encode(Yii::$app->params['siteId'])?><!--/logo.png" alt="logo">--><?//=Html::encode(Yii::$app->params['siteDomain'])?>
                        </a>
                    </div>

                </div>
                <!-- Start MAinmenu Ares -->
                <div class="col-md-8 col-lg-8 col-sm-6 col-xs-6">
                    <nav class="mainmenu__nav hidden-xs hidden-sm">
                        <ul class="main__menu">
                            <!--           TODO: убрать <b> прописать в стилях   <b>Главная</b> to Главная        -->
                            <li class="drop"><a href="<?=Url::toRoute(["/"])?>"><b>Главная</b></a>
                            </li>
                            <li><a href="/catalog/"><b>Каталог</b></a></li>
                            <li class="drop"><a href="portfolio-gutter-box-3.html"><b>Портфолио</b></a>
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
                            <li class="drop"><a href="blog.html"><b>Блог</b></a>
                                <ul class="dropdown">
                                    <li><a href="blog.html">blog 3 column</a></li>
                                    <li><a href="blog-2-col-rightsidebar.html">2 col right siderbar</a></li>
                                    <li><a href="blog-details-left-sidebar.html"> blog details</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.html"><b>Доставка</b></a></li>
                            <li><a href="contact.html"><b>Контакты</b></a></li>
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
                                <a href="<?=Url::toRoute(["/cabinet"])?>" title="Выйти"><span class="ti-settings"></span></a>
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