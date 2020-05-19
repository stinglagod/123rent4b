<?php
/** @var \common\models\Order $order */
$this->title = "Спасибо";
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="our-checkout-area ptb--40 bg__white">
    <div class="container">
        <div class="row">
            <h2>Спасибо за заказ. В ближайшее время наши менеджеры свяжутся с вами!</h2>
            <p>Ваш номер заказа: <?= $order->id?></p>
        </div>
    </div>
</section>
