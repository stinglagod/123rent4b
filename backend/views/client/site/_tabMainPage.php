<?php
use kartik\file\FileInput;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $site \rent\entities\Client\Site */
/* @var $model \rent\forms\manage\Client\SiteForm */
/* @var $form \yii\widgets\ActiveForm */
?>
Главная страница

<div class="box box-primary">
    <div class="box-header">
        Главный слайдер
    </div>
    <div class="box-body">

    </div>
</div>

<?php
//    var_dump($model->mainPage->mainSlider_images[0]->getThumbFileUrl('file', 'logo_153x36'));
//    var_dump($site->mainPage->mainSlider[0]['image']);
?>

<?php //нужен какой-то элемент формы, что бы все загрузилось?>
<?= $form->field($model->mainPage, 'tmp')->label(false)->hiddenInput(['maxlength' => true]) ?>

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
            $mainSliderUrl=$model->mainPage->mainSliders[$key]['image']?$model->mainPage->mainSliders[$key]['image']->getThumbFileUrl('file', 'logo_153x36'):null;
//            var_dump($mainSliderUrl);

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
