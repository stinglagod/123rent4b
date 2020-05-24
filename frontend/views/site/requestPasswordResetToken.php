<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="htc__login__register bg__white ptb--40">
    <div class="container">
        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <ul class="login__register__menu" role="tablist">
                    <li role="presentation" class="login active"><a href="#login" role="tab" data-toggle="tab">Восстановление</a></li>
                </ul>
                <p style="text-align: center;">Для восстановления пароля введите ваш email.</p>
            </div>
        </div>
        <!-- Start Login Register Content -->
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="htc__login__register__wrap">
                    <!-- Start Single Content -->
                    <div id="login" role="tabpanel" class="single__tabs__panel tab-pane fade in active">
                        <?php $form = ActiveForm::begin([
                            'id' => 'request-password-reset-form',
                            'enableClientValidation' => false,
                            'options' => [
                                'class'=> 'login'
                            ],
                            'fieldConfig' => [
//                                'template' => "{input}",
                                'options' => [
                                    'tag' => false,
                                ],
                            ],

                        ]); ?>
                        <?= $form
                            ->field($model, 'email')
                            ->label(false)
                            ->textInput(['placeholder' => $model->getAttributeLabel('email'),'class' => ''])
                        ?>

                        <div class="htc__login__btn mt--30">
                            <a href="#" onclick="$(this).closest('form').submit();">Восстановить</a>
                        </div>
                        <?php ActiveForm::end(); ?>

                    </div>
                    <!-- End Single Content -->
                </div>
            </div>
        </div>
        <!-- End Login Register Content -->
    </div>
</div>
<!--<div class="site-request-password-reset">-->
<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
<!---->
<!--    <p>Please fill out your email. A link to reset password will be sent there.</p>-->
<!---->
<!--    <div class="row">-->
<!--        <div class="col-lg-5">-->
<!--            --><?php //$form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
<!---->
<!--                --><?//= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
<!--                </div>-->
<!---->
<!--            --><?php //ActiveForm::end(); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
