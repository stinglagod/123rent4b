<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \rent\forms\auth\LoginForm */
/* @var $signup \rent\forms\auth\SignupForm */
/** @var $focus ['login'|'signup']*/

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;

$this->title = 'Войти';
$this->params['breadcrumbs'][] = $this->title;
$this->params['h1']=$this->title;
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
                            <span class="forget"><?= Html::a('Забыли пароль?', ['auth/reset/request']) ?></span>
                        </div>
                        <div class="htc__login__btn mt--30">
                            <a href="#" onclick="$(this).closest('form').submit();">Войти</a>
                        </div>
                        <?php ActiveForm::end(); ?>

                        <div class="htc__social__connect">
                            <h2>или войти через</h2>
                            <?= yii\authclient\widgets\AuthChoice::widget([
                                'baseAuthUrl' => ['auth/network/auth']
                            ]); ?>
                        </div>
                    </div>
                    <!-- End Single Content -->
                    <!-- Start Single Content -->
                    <div id="register" role="tabpanel" class="single__tabs__panel tab-pane fade <?=($focus=='signup')?'in active':''?>">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'enableClientValidation' => false,
                            'action' => \yii\helpers\Url::toRoute(['auth/signup/request']),
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
                        <?= $form->errorSummary($model); ?>
                        <?= $form
                            ->field($signup, 'name')
                            ->label(false)
                            ->textInput(['placeholder' => $signup->getAttributeLabel('name'),'class' => ''])
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
                        <?= $form
                            ->field($signup, 'password_repeat')
                            ->label(false)
                            ->passwordInput(['placeholder' => $signup->getAttributeLabel('password_repeat'),'class' => ''])
                        ?>
                        <?= (Yii::$app->settings->site->reCaptcha->google_secretV3 and YII_ENV_PROD)?$form->field($signup, 'reCaptcha')->widget(
                            \himiklab\yii2\recaptcha\ReCaptcha3::class,
                            [
                                'siteKey' => Yii::$app->settings->site->reCaptcha->google_siteKeyV3, // unnecessary is reCaptcha component was set up
                                'action' => 'signup',
                            ]
                        )->label(false):'' ?>

                        <div class="htc__login__btn">
                            <a id="btn_register" href="#" onclick="$(this).closest('form').submit();">Регистрация</a>
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
