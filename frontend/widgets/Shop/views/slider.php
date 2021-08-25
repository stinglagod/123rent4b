<?php
use yii\helpers\Url;
use \yii\helpers\Html;

/** @var \rent\entities\Client\File $image */
$image=$sliders[0]['image'];
//var_dump($image->getThumbFileUrl('file','1920x800'));exit;
?>


<div class="slider__container slider--one">
    <div class="slider__activation__wrap owl-carousel owl-theme">
<?php
/** @var array sliders */
foreach ($sliders as $key => $slide):
    if (empty($slide['image'])) {
        continue;
    }
    ?>
    <div class="slide slider__full--screen slider-height-inherit slider-text-right" style="background: rgba(0, 0, 0, 0) url(<?=Html::encode($slide['image']->getThumbFileUrl('file', '870x368'))?>) no-repeat scroll center center / cover ;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-lg-8 col-md-offset-2 col-lg-offset-4 col-sm-12 col-xs-12">
                    <div class="slider__inner">
<!--  TODO: убрать h1, заменить на кокой-нибудь другой, Например span. С сохранение текогоже отобржениея                      -->
<!--                        <h1>--><?//=(!array_key_exists($key,$firstTexts))?:Html::encode($firstTexts[$key])?><!--<span class="text--theme">--><?//=(!array_key_exists($key,$secondTexts))?:Html::encode($secondTexts[$key])?><!--</span></h1>-->
                        <h1><?=Html::encode($slide['text'])?><span class="text--theme"><?=Html::encode($slide['text2'])?></span></h1>
                        <?php if ($slide['url']): ?>
                            <div class="slider__btn">
                                <a class="htc__btn" href="<?=Html::encode($slide['url'])?>"><?=Html::encode($slide['urlText'])?></a>
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
