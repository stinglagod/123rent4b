<?php
/* @var $this \yii\web\View */
/* @var $content string */
use frontend\widgets\Shop\CategoriesWidget;
use frontend\widgets\Shop\SliderWidget;
use frontend\widgets\Shop\BannerWidget;
use frontend\widgets\Shop\ProductCategoriesWidget;
?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>
<?php
//    var_dump(Yii::$app->params['siteId']);
//    var_dump(Yii::$app->params['mainPage']->categories[0]);
//    exit;
?>
<!-- Start Feature Product -->
<section class="categories-slider-area bg__white">
    <div class="container">
        <div class="row">
            <!-- Start Left Feature -->
            <div class="col-md-9 col-lg-9 col-sm-8 col-xs-12 float-left-style">
                <!-- Start Slider Area -->
                <?= SliderWidget::widget([
                        'sliders' => Yii::$app->params['mainPage']->mainSlider,
                        'images' => ['images/slider/bg/1.png','images/slider/bg/2.png'],
                        'firstTexts' => ['New Product'],
                        'secondTexts' => ['Collection'],
                        'urls'=>['cart.html'],
                        'urlTexts'=>['каталог'],
                ]) ?>
                <!-- Start Slider Area -->
            </div>
            <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12 float-right-style">
                <?= CategoriesWidget::widget([
                    'name' => 'Каталог',
                    'active' => $this->params['active_category'] ?? null
                ]) ?>
            </div>
            <!-- End Left Feature -->
        </div>
    </div>
</section>
<!-- End Feature Product -->
<?= BannerWidget::widget([
    'content'=>Yii::$app->params['mainPage']->banners[0],
]) ?>
<!-- Start Our Product Area -->
<?= ProductCategoriesWidget::widget([
    'content'=>Yii::$app->params['mainPage']->categories[0]
]) ?>
<!-- End Our Product Area -->
<?= BannerWidget::widget([
    'content'=>Yii::$app->params['mainPage']->banners[1],
]) ?>
<!-- Start Our Product Area -->
<?= frontend\widgets\Shop\ProductCategoriesWidget::widget() ?>
<!-- End Our Product Area -->
<?= BannerWidget::widget([
    'content'=>Yii::$app->params['mainPage']->banners[2],
]) ?>
<!-- Start Our Product Area -->
<?= frontend\widgets\Shop\ProductCategoriesWidget::widget() ?>
<!-- End Our Product Area -->
<!-- Start Blog Area -->
<?= frontend\widgets\Shop\BlogWidget::widget() ?>
<!-- End Blog Area -->
<?php $this->endContent() ?>
