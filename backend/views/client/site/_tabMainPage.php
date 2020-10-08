<?php
use kartik\file\FileInput;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $site \rent\entities\Client\Site */
/* @var $model \rent\forms\manage\Client\Site\SiteForm */
/* @var $form \yii\widgets\ActiveForm */
?>

<?php
//    var_dump($model->mainPage);exit;
?>
<?php //нужен какой-то элемент формы, что бы все загрузилось?>
<?= $form->field($model->mainPage, 'tmp')->label(false)->hiddenInput(['maxlength' => true]) ?>

<div class="box box-primary">
    <div class="box-header">
        Главный слайдер (1920x800)
    </div>
    <div class="box-body">
        <?php foreach ($model->mainPage->mainSliders as $key => $item) :?>
            <div class="col-md-3">
                <div class="btn-group">
                    <?php if ($key!=0) {
                        echo Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-main_slider-up', 'id' => $site->id, 'key' => $key], [
                            'class' => 'btn btn-default',
                            'data-method' => 'post',
                        ]);
                    }
                    ?>
                    <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-main_slider', 'id' => $site->id, 'key' => $key], [
                        'class' => 'btn btn-default',
                        'data-method' => 'post',
                        'data-confirm' => 'Remove slide?',
                    ]); ?>
                    <?php if (($key)!=count($model->mainPage->mainSliders)-1) {
                        echo Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', ['move-main_slider-down', 'id' => $site->id, 'key' => $key], [
                            'class' => 'btn btn-default',
                            'data-method' => 'post',
                        ]);
                    } ?>
                </div>
                <?php
                $mainSliderUrl=$model->mainPage->mainSliders[$key]['image']?$model->mainPage->mainSliders[$key]['image']->getThumbFileUrl('file', '1920x800'):null;
                ?>
                <?= $form->field($item, '[' . $key . ']image')->label(false)->widget(FileInput::class, [
                    'options' => [
                        'id' => 'main-slider-'.$key,
                        'accept' => 'image/*',
                        'multiple' => false,
                    ],
                    'pluginOptions' => [
                        'initialPreview'=>[
                            $mainSliderUrl
                        ],
                        'showPreview' => true,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'initialPreviewAsData'=>true,
                    ]
                ]) ?>
                <?= $form->field($item, '[' . $key . ']text')->textInput(['maxlength' => true]) ?>
                <?= $form->field($item, '[' . $key . ']text2')->textInput(['maxlength' => true]) ?>
                <?= $form->field($item, '[' . $key . ']url')->textInput(['maxlength' => true]) ?>
                <?= $form->field($item, '[' . $key . ']urlText')->textInput(['maxlength' => true]) ?>
            </div>
        <?php endforeach;?>
    </div>
</div>


<div class="box box-primary">
    <div class="box-header">
        Баннер 1 (1171x300)
    </div>
    <div class="box-body">
        <?php
        $key=0;
        $bannerImageUrl=$model->mainPage->banners[$key]['image']?$model->mainPage->banners[$key]['image']->getThumbFileUrl('file', '1171x300'):null;
        ?>
        <?= $form->field($model->mainPage->banners[$key], '[' . $key . ']image')->label(false)->widget(FileInput::class, [
            'options' => [
                'id' => 'banner-'.$key,
                'accept' => 'image/*',
                'multiple' => false,
            ],
            'pluginOptions' => [
                'initialPreview'=>[
                    $bannerImageUrl
                ],
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
                'initialPreviewAsData'=>true,
            ]
        ]) ?>
        <?= $form->field($model->mainPage->banners[$key], '['.$key.']name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model->mainPage->banners[$key], '['.$key.']url')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        Категория 1
    </div>
    <div class="box-body">
        <?php
            $key=0;
        ?>
        <?= $form->field($model->mainPage->categories[$key], '['.$key.']category_id')->dropDownList($model->mainPage->categories[$key]->categoriesList(), ['prompt' => '']) ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        Баннер 2 (1171x300)
    </div>
    <div class="box-body">
        <?php
        $key=1;
        $bannerImageUrl=$model->mainPage->banners[$key]['image']?$model->mainPage->banners[$key]['image']->getThumbFileUrl('file', 'logo_153x36'):null;
        ?>
        <?= $form->field($model->mainPage->banners[$key], '[' . $key . ']image')->label(false)->widget(FileInput::class, [
            'options' => [
                'id' => 'banner-'.$key,
                'accept' => 'image/*',
                'multiple' => false,
            ],
            'pluginOptions' => [
                'initialPreview'=>[
                    $bannerImageUrl
                ],
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
                'initialPreviewAsData'=>true,
            ]
        ]) ?>
        <?= $form->field($model->mainPage->banners[$key], '['.$key.']name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model->mainPage->banners[$key], '['.$key.']url')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        Категория 2
    </div>
    <div class="box-body">
        <?php
        $key=1;
        ?>
        <?= $form->field($model->mainPage->categories[$key], '['.$key.']category_id')->dropDownList($model->mainPage->categories[$key]->categoriesList(), ['prompt' => '']) ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        Баннер 3 (1171x300)
    </div>
    <div class="box-body">
        <?php
        $key=2;
        $bannerImageUrl=$model->mainPage->banners[$key]['image']?$model->mainPage->banners[$key]['image']->getThumbFileUrl('file', 'logo_153x36'):null;

        ?>
        <?= $form->field($model->mainPage->banners[$key], '[' . $key . ']image')->label(false)->widget(FileInput::class, [
            'options' => [
                'id' => 'banner-'.$key,
                'accept' => 'image/*',
                'multiple' => false,
            ],
            'pluginOptions' => [
                'initialPreview'=>[
                    $bannerImageUrl
                ],
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
                'initialPreviewAsData'=>true,
            ]
        ]) ?>
        <?= $form->field($model->mainPage->banners[$key], '['.$key.']name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model->mainPage->banners[$key], '['.$key.']url')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="box box-primary">
    <div class="box-header">
        Категория 3
    </div>
    <div class="box-body">
        <?php
        $key=2;
        ?>
        <?= $form->field($model->mainPage->categories[$key], '['.$key.']category_id')->dropDownList($model->mainPage->categories[$key]->categoriesList(), ['prompt' => '']) ?>
    </div>
</div>

