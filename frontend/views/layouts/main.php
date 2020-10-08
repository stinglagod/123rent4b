<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\widgets\Pjax;
use yii\helpers\Url;
use frontend\widgets\Shop\CartWidget;

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
    <link href="<?= Html::encode(Url::canonical()) ?>" rel="canonical"/>
    <link href="<?= Yii::getAlias('@web/images/catalog/cart.png') ?>" rel="icon"/>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Body main wrapper start -->
<div class="wrapper fixed__footer">

    <?=$this->render('_header');?>

    <div class="body__overlay"></div>
    <!-- Start Offset Wrapper -->
    <div class="offset__wrapper">
        <?=$this->render('_search');?>
        <?php Pjax::begin(['id'=>'pjax-mini-cart']); ?>
        <?= CartWidget::widget() ?>
        <?php Pjax::end() ?>
    </div>
    <!-- End Offset Wrapper -->

<!--    --><?php //if (empty($this->params['mainPage'])) {
//        $this->render('_breadcrumb');
//    } else {
//        $this->render('_main');
//    }
//    ?>

    <div class="container">
        <?php Pjax::begin(['id' => 'pjax_alerts']) ?>
        <?= Alert::widget() ?>
        <?php Pjax::end() ?>
    </div>
    <?= $content ?>

    <?=$this->render('_footer');?>
</div>
<!-- Body main wrapper end -->
<?=$this->render('_quickview');?>
<!--Общее модальное окно-->

<div id="modalBlock"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
