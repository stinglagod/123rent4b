<?php
/* @var $this \yii\web\View */

use frontend\widgets\Shop\CartWidget;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\widgets\Alert;

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
                            <img src="/uploads/sites/<?=Html::encode(Yii::$app->settings->site->id)?>/logo.png" alt="logo">
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
                            <li><a href="/delivery"><b>Доставка</b></a></li>
                            <li><a href="/contact"><b>Контакты</b></a></li>
                        </ul>
                    </nav>
                    <div class="mobile-menu clearfix visible-xs visible-sm">
                        <nav id="mobile_dropdown">
                            <ul>
                                <li><a href="<?=Url::toRoute(["/"])?>"><?=Url::toRoute(["/"])?>"</a>
                                </li>
                                <li><a href="/catalog/"><b>Каталог</b></a></li>
                                <li><a href="/delivery"><b>Доставка</b></a></li>
                                <li><a href="/contact"><b>Контакты</b></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- End MAinmenu Ares -->
                <div class="col-md-2 col-sm-4 col-xs-3">
                    <ul class="menu-extra">
                        <li class="hidden-xs" title="Избранное"> <a href="<?=Url::toRoute(["cabinet/wishlist"])?>"> <span class="ti-star" id="icn_wishlist"></span></a></li>
                        <li class="search search__open hidden-xs" title="Поиск"><span class="ti-search"></span></li>
                        <li>
                            <?php if (Yii::$app->user->isGuest) { ?>
                                <a href="<?=Url::toRoute(["site/login"])?>" title="Войти" ><span class="ti-user"></span></a>
                            <?php } else { ?>
                                <a href="<?=Url::toRoute(["/cabinet"])?>" title="Выйти"><span class="ti-settings"></span></a>
                            <?php }?>
                        </li>
                        <li class="cart__menu"><span class="ti-shopping-cart" id="icn_cart"></span></li>
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
<div class="offset__wrapper" >
    <?=$this->render('_search');?>
    <div id="mini-cart">
        <?= CartWidget::widget() ?>
    </div>
</div>
<!-- End Offset Wrapper -->

<!--    --><?php //if (empty($this->params['mainPage'])) {
//        $this->render('_breadcrumb');
//    } else {
//        $this->render('_main');
//    }
//    ?>

<div class="container">
    <?php Pjax::begin(['id' => 'pjax_alerts','timeout' => 5000]) ?>
    <?= Alert::widget() ?>
    <?php Pjax::end() ?>
</div>