<?php
use yii\helpers\Url;
use \yii\helpers\Html;
?>


<div class="slider__container slider--one">
    <div class="slider__activation__wrap owl-carousel owl-theme">
<?php
/** @var array $images */
foreach ($images as $key => $image): ?>
    <div class="slide slider__full--screen slider-height-inherit slider-text-right" style="background: rgba(0, 0, 0, 0) url(<?=Html::encode($image)?>) no-repeat scroll center center / cover ;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-lg-8 col-md-offset-2 col-lg-offset-4 col-sm-12 col-xs-12">
                    <div class="slider__inner">
<!--  TODO: убрать h1, заменить на кокой-нибудь другой, Например span. С сохранение текогоже отобржениея                      -->
                        <h1><?=(!array_key_exists($key,$firstTexts))?:Html::encode($firstTexts[$key])?><span class="text--theme"><?=(!array_key_exists($key,$secondTexts))?:Html::encode($secondTexts[$key])?></span></h1>
                        <?php if (array_key_exists($key,$urls) and (array_key_exists($key,$urls))): ?>
                            <div class="slider__btn">
                                <a class="htc__btn" href="<?=Html::encode($urls[$key])?>"><?=Html::encode($urlTexts[$key])?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
    </div>
</div>
