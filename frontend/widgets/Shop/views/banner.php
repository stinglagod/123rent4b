<?php
use yii\helpers\Url;
use \yii\helpers\Html;
?>


<div class="only-banner ptb--100 bg__white">
    <div class="container">
        <div class="only-banner-img">
            <a href="<?=empty($url)?:Html::encode($url)?>"><img src="<?=Html::encode($image)?>" alt="<?=empty($name)?:Html::encode($name)?>"></a>
        </div>
    </div>
</div>
