<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var \common\models\User $user */
/** @var \common\models\Order $order */
$this->title = "Оформление заказа";
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="our-checkout-area ptb--40 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-lg-8">
                <?php $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => "{input}",
                        'options' => [
                            'tag' => false,
                        ],
                    ],
                ]);
                ?>
                <div class="ckeckout-left-sidebar">
                    <!-- Start Checkbox Area -->
                    <div class="checkout-form">
                        <h2 class="section-title-3">Детали заказа №<?=$order->id?></h2>
                        <div class="checkout-form-inner">
                            <div class="single-checkout-box">
                                <?= $form->field($order, 'customer')->textInput( ['placeholder' => '<Имя>*', 'class' => ''])->label('Имя') ?>
                                <?= $form->field($order, 'telephone')->textInput(['placeholder' => '<Телефон>*', 'class' => ''])->label('Телефон') ?>
                            </div>
                            <div class="single-checkout-box">
                                <?= $form->field($order, 'comment')->textarea(['placeholder' => '<Сообщение>', 'class' => ''])->label('Сообщение') ?>
                            </div>
                        </div>
                    </div>
                    <!-- End Checkbox Area -->
                    <div class="wc-proceed-to-checkout ">
                        <a href="#" type="" onclick="$(this).closest('form').submit();">Оформить заказ</a>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="checkout-right-sidebar">
                    <div class="our-important-note">
                        <h2 class="section-title-3">Примечание :</h2>
                        <p class="note-desc">При оформлении вы соглашаетесь с <a href="#">правилами.</a>
                        <br>
                            Основные требования:
                        </p>
                        <ul class="important-note">
                            <li><a href="#"><i class="zmdi zmdi-caret-right-circle"></i>Залоговая сумма 1000 рублей</a></li>
                            <li><a href="#"><i class="zmdi zmdi-caret-right-circle"></i>Lorem ipsum dolor sit amet</a></li>
                            <li><a href="#"><i class="zmdi zmdi-caret-right-circle"></i>Lorem ipsum dolor sit amet, consectetur nipabali</a></li>
                            <li><a href="#"><i class="zmdi zmdi-caret-right-circle"></i>Lorem ipsum dolor sit amet, consectetur nipabali</a></li>
                            <li><a href="#"><i class="zmdi zmdi-caret-right-circle"></i>подробнее ...</a></li>
                        </ul>
                    </div>
                    <div class="puick-contact-area mt--60">
                        <h2 class="section-title-3">Быстрая связь</h2>
                        <p class="note-desc">Возникли вопросы?</p>
                        <a href="phone:+8801722889963">+012 345 678 102 </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>