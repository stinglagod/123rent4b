<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Войти';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="htc__login__register bg__white ptb--40">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <ul class="login__register__menu" role="tablist">
                    <li role="presentation" class="login <?=($focus=='login')?'active':''?>"><a href="#login" role="tab" data-toggle="tab">Войти</a></li>
                    <li role="presentation" class="register <?=($focus=='signup')?'active':''?>"><a href="#register" role="tab" data-toggle="tab">Регистрация</a></li>
                </ul>
            </div>
        </div>
        <!-- Start Login Register Content -->
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="htc__login__register__wrap">
                    <!-- Start Single Content -->
                    <div id="login" role="tabpanel" class="single__tabs__panel tab-pane fade  <?=($focus=='login')?'in active':''?>">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
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
                        <?= $form
                            ->field($model, 'password')
                            ->label(false)
                            ->passwordInput(['placeholder' => $model->getAttributeLabel('password'),'class' => ''])
                        ?>
                        <div class="tabs__checkbox">
                            <span class="forget"><a href="request-password-reset">Забыли пароль?</a></span>
                        </div>
                        <div class="htc__login__btn mt--30">
                            <a href="#" onclick="$(this).closest('form').submit();">Войти</a>
                        </div>
                        <?php ActiveForm::end(); ?>

                        <div class="htc__social__connect">
                            <h2>или войти через</h2>
                            <ul class="htc__soaial__list">
                                <li><a class="bg--twitter" href="#"><i class="zmdi zmdi-twitter"></i></a></li>

                                <li><a class="bg--instagram" href="#"><i class="zmdi zmdi-instagram"></i></a></li>

                                <li><a class="bg--facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>

                                <li><a class="bg--googleplus" href="#"><i class="zmdi zmdi-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Content -->
                    <!-- Start Single Content -->
                    <div id="register" role="tabpanel" class="single__tabs__panel tab-pane fade <?=($focus=='signup')?'in active':''?>">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'enableClientValidation' => false,
                            'action' => \yii\helpers\Url::toRoute(['site/signup']),
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
                            ->field($signup, 'name')
                            ->label(false)
                            ->textInput(['placeholder' => $signup->getAttributeLabel('name'),'class' => ''])
                        ?>
                        <?= $form
                            ->field($signup, 'surname')
                            ->label(false)
                            ->textInput(['placeholder' => $signup->getAttributeLabel('surname'),'class' => ''])
                        ?>
                        <?= $form
                            ->field($signup, 'email')
                            ->label(false)
                            ->textInput(['placeholder' => $signup->getAttributeLabel('email'),'class' => ''])
                        ?>
                        <?= $form
                            ->field($signup, 'password')
                            ->label(false)
                            ->passwordInput(['placeholder' => $signup->getAttributeLabel('password'),'class' => ''])
                        ?>
                        <div class="htc__login__btn">
                            <a href="#" onclick="$(this).closest('form').submit();">Регистрация</a>
                        </div>
                        <?php ActiveForm::end(); ?>
                        <div class="htc__social__connect">
                            <h2>Или войти через</h2>
                            <ul class="htc__soaial__list">
                                <li><a class="bg--twitter" href="#"><i class="zmdi zmdi-twitter"></i></a></li>
                                <li><a class="bg--instagram" href="#"><i class="zmdi zmdi-instagram"></i></a></li>
                                <li><a class="bg--facebook" href="#"><i class="zmdi zmdi-facebook"></i></a></li>
                                <li><a class="bg--googleplus" href="#"><i class="zmdi zmdi-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Single Content -->
                </div>
            </div>
        </div>
        <!-- End Login Register Content -->
    </div>
</div>

<!--<div class="site-login">-->
<!--    <h1>--><?//= Html::encode($this->title) ?><!--</h1>-->
<!---->
<!--    <p>Please fill out the following fields to login:</p>-->
<!---->
<!--    <div class="row">-->
<!--        <div class="col-lg-5">-->
<!--            --><?php //$form = ActiveForm::begin(['id' => 'login-form']); ?>
<!---->
<!--                --><?//= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
<!---->
<!--                --><?//= $form->field($model, 'password')->passwordInput() ?>
<!---->
<!--                --><?//= $form->field($model, 'rememberMe')->checkbox() ?>
<!---->
<!--                <div style="color:#999;margin:1em 0">-->
<!--                    If you forgot your password you can --><?//= Html::a('reset it', ['site/request-password-reset']) ?><!--.-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
<!--                </div>-->
<!---->
<!--            --><?php //ActiveForm::end(); ?>
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
