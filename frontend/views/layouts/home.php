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
//    var_dump(Yii::$app->params['mainPage']->mainSlider);
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
        'image'=>'images/new-product/3.jpg',
        'name' => 'new product',
        'url' => 'shop-sidebar.html'
]) ?>
<!-- Start Our Product Area -->
<?= frontend\widgets\Shop\ProductCategoriesWidget::widget([
    'category'=>'tt'
]) ?>
<!-- End Our Product Area -->
<?= BannerWidget::widget([
    'image'=>'images/new-product/6.jpg',
    'name' => 'new product',
    'url' => 'shop-sidebar.html'
]) ?>
<!-- Start Our Product Area -->
<?= frontend\widgets\Shop\ProductCategoriesWidget::widget() ?>
<!-- End Our Product Area -->
<?= BannerWidget::widget([
    'image'=>'images/new-product/7.jpg',
    'name' => 'new product',
    'url' => 'shop-sidebar.html'
]) ?>
<!-- Start Our Product Area -->
<?= frontend\widgets\Shop\ProductCategoriesWidget::widget() ?>
<!-- End Our Product Area -->
<!-- Start Blog Area -->
<?= frontend\widgets\Shop\BlogWidget::widget() ?>
<!-- End Blog Area -->
<?php $this->endContent() ?>
