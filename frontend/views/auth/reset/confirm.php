<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \rent\forms\auth\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Сброс пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="htc__login__register bg__white ptb--40">
    <div class="container">
        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <ul class="login__register__menu" role="tablist">
                    <li role="presentation" class="login active"><a href="#login" role="tab" data-toggle="tab">Сброс пароля</a></li>
                </ul>
                <p style="text-align: center;">Введите новый пароль</p>
            </div>
        </div>
        <!-- Start Login Register Content -->
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="htc__login__register__wrap">
                    <!-- Start Single Content -->
                    <div id="login" role="tabpanel" class="single__tabs__panel tab-pane fade in active">
                        <?php $form = ActiveForm::begin([
                            'id' => 'reset-password-form',
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
                            ->field($model, 'password')
                            ->label(false)
                            ->passwordInput(['placeholder' => $model->getAttributeLabel('password'),'class' => ''])
                        ?>

                        <div class="htc__login__btn mt--30">
                            <a href="#" onclick="$(this).closest('form').submit();">Сменить</a>
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
<!--<div class="site-reset-password">-->
<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
<!---->
<!--    <p>Please choose your new password:</p>-->
<!---->
<!--    <div class="row">-->
<!--        <div class="col-lg-5">-->
<!--            --><?php //$form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
<!---->
<!--                --><?//= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
<!--                </div>-->
<!---->
<!--            --><?php //ActiveForm::end(); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
