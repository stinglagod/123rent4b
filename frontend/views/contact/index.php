<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \rent\forms\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

<!-- Start Contact Area -->
<section class="htc__contact__area ptb--120 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                <div class="htc__contact__container">
                    <div class="htc__contact__address">
                        <h2 class="contact__title">contact info</h2>
                        <div class="contact__address__inner">
                            <!-- Start Single Adress -->
                            <div class="single__contact__address">
                                <div class="contact__icon">
                                    <span class="ti-location-pin"></span>
                                </div>
                                <div class="contact__details">
                                    <p>Адрес : <br> <?=Html::encode(Yii::$app->params['address'])?></p>
                                </div>
                            </div>
                            <!-- End Single Adress -->
                        </div>
                        <div class="contact__address__inner">
                            <!-- Start Single Adress -->
                            <div class="single__contact__address">
                                <div class="contact__icon">
                                    <span class="ti-mobile"></span>
                                </div>
                                <div class="contact__details">
                                    <p> Телефон : <br><a href="tel:<?=Html::encode(Yii::$app->params['telephone'])?>"><?=Html::encode(Yii::$app->params['telephone'])?> </a></p>
                                </div>
                            </div>
                            <!-- End Single Adress -->
                            <!-- Start Single Adress -->
                            <div class="single__contact__address">
                                <div class="contact__icon">
                                    <span class="ti-email"></span>
                                </div>
                                <div class="contact__details">
                                    <p> Mail :<br><a href="mailto:<?=Html::encode(Yii::$app->params['email'])?>"><?=Html::encode(Yii::$app->params['email'])?></a></p>
                                </div>
                            </div>
                            <!-- End Single Adress -->
                        </div>
                    </div>
                    <div class="contact-form-wrap">
                        <div class="contact-title">
                            <h2 class="contact__title">Связаться с нами</h2>
                        </div>
                        <?php $form = ActiveForm::begin([
                            'id' => 'contact-form',
                            'fieldConfig' => [
                                'options' => [
                                    'tag' => false,
                                ],
                            ],

                        ]); ?>
                            <div class="single-contact-form">
                                <div class="contact-box name">
                                    <?= $form
                                        ->field($model, 'name')
                                        ->label(false)
                                        ->textInput(['placeholder' => $model->getAttributeLabel('name'),'class' => ''])
                                    ?>
                                    <?= $form
                                        ->field($model, 'email')
                                        ->label(false)
                                        ->textInput(['placeholder' => $model->getAttributeLabel('email'),'class' => ''])
                                    ?>
                                </div>
                            </div>
                            <div class="single-contact-form">
                                <div class="contact-box subject">
                                    <?= $form
                                        ->field($model, 'subject')
                                        ->label(false)
                                        ->textInput(['placeholder' => $model->getAttributeLabel('subject'),'class' => ''])
                                    ?>
                                </div>
                            </div>
                            <div class="single-contact-form">
                                <div class="contact-box message">
                                    <?= $form
                                        ->field($model, 'body')
                                        ->label(false)
                                        ->textarea(['placeholder' => $model->getAttributeLabel('body'),'class' => '','rows' => 3])
                                    ?>
                                </div>
                            </div>
                            <div class="contact-btn">
                                <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                                ]) ?>
                                <button type="submit" class="fv-btn">Отправить</button>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="form-output">
                        <p class="form-messege"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 smt-30 xmt-30">
                <div class="map-contacts">
                    <div id="googleMap"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Area -->
