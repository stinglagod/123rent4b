<?php
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 21.10.2019
 * Time: 14:21
 */
use \yii\helpers\Url;
use kartik\editable\Editable;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

/** @var $order \common\models\Order */



?>

<!-- Start Cart Panel -->
<div class="shopping__cart">

    <div class="shopping__cart__inner">
        <div class="offsetmenu__close__btn">
            <a href="#"><i class="zmdi zmdi-close"></i></a>
        </div>
        <div class="row">
            <p> Для добавления в корзину необходимо авторизироваться: </p>
            <div id="login" role="tabpanel" class="single__tabs__panel tab-pane fade in active">
                <form class="login" method="post">
                    <input type="text" placeholder="Email*">
                    <input type="password" placeholder="Пароль*">
                </form>
                <div class="tabs__checkbox">
                    <input type="checkbox">
                    <span> Запомнить меня</span>
                    <span class="forget"><a href="#">Забыли пароль?</a></span>
                </div>
                <div class="htc__login__btn mt--30">
                    <a href="#">Войти</a>
                </div>
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

        </div>
        <br>

    </div>
</div>
