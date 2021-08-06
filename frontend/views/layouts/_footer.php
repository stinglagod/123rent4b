<?php
/* @var $this \yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $site \rent\entities\Client\Site */
$site=Yii::$app->settings->site;
?>
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
<!--                                <img src="/uploads/sites/--><?//=Html::encode(Yii::$app->params['siteId'])?><!--/logo.png" alt="footer logo">-->
                            </a>
                        </div>
                        <div class="footer-address">
                            <ul>
                                <li>
                                    <div class="address-icon">
                                        <i class="zmdi zmdi-pin"></i>
                                    </div>
                                    <div class="address-text">
                                        <p><?=Html::encode($site->address)?></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="address-icon">
                                        <i class="zmdi zmdi-email"></i>
                                    </div>
                                    <div class="address-text">
                                        <a href="mailto:<?=Html::encode($site->email)?>"><?=Html::encode($site->email)?></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="address-icon">
                                        <i class="zmdi zmdi-phone-in-talk"></i>
                                    </div>
                                    <div class="address-text">
                                        <p><?=Html::encode($site->telephone)?></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <ul class="social__icon">
                            <?php if ($site->social->urlTwitter): ?>
                            <li><a href="<?=Html::encode($site->social->urlTwitter)?>"><i class="zmdi zmdi-twitter"></i></a></li>
                            <?php endif;?>
                            <?php if ($site->social->urlInstagram): ?>
                            <li><a href="<?=Html::encode($site->social->urlInstagram)?>"><i class="zmdi zmdi-instagram"></i></a></li>
                            <?php endif;?>
                            <?php if ($site->social->urlFacebook): ?>
                            <li><a href="<?=Html::encode($site->social->urlFacebook)?>"><i class="zmdi zmdi-facebook"></i></a></li>
                            <?php endif;?>
                            <?php if ($site->social->urlGooglePlus): ?>
                            <li><a href="<?=Html::encode($site->social->urlGooglePlus)?>"><i class="zmdi zmdi-google-plus"></i></a></li>
                            <?php endif;?>
                            <?php if ($site->social->urlVk): ?>
                            <li><a href="<?=Html::encode($site->social->urlVk)?>"><i class="zmdi zmdi-vk"></i></a></li>
                            <?php endif;?>
                            <?php if ($site->social->urlOk): ?>
                            <li><a href="<?=Html::encode($site->social->urlOk)?>"><i class="zmdi zmdi-odnoklassniki"></i></a></li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
                <!-- End Single Footer Widget -->
                <!-- Start Single Footer Widget -->
                <div class="col-md-3 col-lg-2 col-sm-6 smt-30 xmt-30">
                    <div class="ft__widget">
                        <h2 class="ft__title">Категории</h2>
                        <ul class="footer-categories">
                            <?php foreach ($site->footer->categories as $category)  :?>
                                <?php if ($category['category']) :?>
                                    <li><a href="<?=Url::toRoute(['/shop/catalog/category','id'=>$category['category']->id])?>"><?=$category['category']->name?></a></li>
                                <?php endif;?>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
                <!-- Start Single Footer Widget -->
                <div class="col-md-3 col-lg-2 col-sm-6 smt-30 xmt-30">
                    <div class="ft__widget">
                        <h2 class="ft__title">Инфрмация</h2>
                        <ul class="footer-categories">
                            <li><a href="/about">О нас</a></li>
                            <li><a href="/contact">Контакты</a></li>
                            <li><a href="/terms">Правила пользования</a></li>
                            <li><a href="/delivery">Доставка</a></li>
                        </ul>
                    </div>
                </div>
                <!-- Start Single Footer Widget -->
                <div class="col-md-3 col-lg-3 col-lg-offset-1 col-sm-6 smt-30 xmt-30">
                    <div class="ft__widget">
                        <h2 class="ft__title">Подписка</h2>
                        <div class="newsletter__form">
                            <p>Подпишитесь на наши новости</p>
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
                            <p>© 2021 <a href="#">RENT4B</a>
                                All Right Reserved.</p>
                        </div>
                        <ul class="footer__menu">
                            <li><a href="/">Главная</a></li>
                            <li><a href="/catalog/">Каталог</a></li>
                            <li><a href="/contact">Контакты</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Copyright Area -->
    </div>
</footer>
<!-- End Footer Area -->