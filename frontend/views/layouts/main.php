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
    <?=Yii::$app->settings->site->counter->yandex_webmaster?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="<?= Html::encode(Url::canonical()) ?>" rel="canonical"/>
<!--    <link href="--><?//= Yii::getAlias('@web/images/catalog/cart.png') ?><!--" rel="icon"/>-->
    <?php $this->head() ?>
    <?=Yii::$app->settings->site->counter->google_tag?>
</head>
<body>
<?=Yii::$app->settings->site->counter->google_tag?>
<?=Yii::$app->settings->site->counter->google_counter?>
<?=Yii::$app->settings->site->counter->yandex_counter?>
<?=Yii::$app->settings->site->counter->facebook_pixel?>
<?php $this->beginBody() ?>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<!-- Body main wrapper start -->
<div class="wrapper fixed__footer">

    <?=$this->render('_header');?>

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
