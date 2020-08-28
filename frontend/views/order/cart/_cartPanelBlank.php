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
                <div class="htc__login__btn mt--30">
                    <a href="<?=Url::toRoute(["site/login"])?>">Войти</a>
                    <a href="<?=Url::toRoute(["site/signup"])?>">Регистрация</a>
                </div>
            </div>

        </div>
        <br>

    </div>
</div>
