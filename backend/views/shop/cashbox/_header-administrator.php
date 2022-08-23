<?php

use rent\helpers\PaymentHelper;
use rent\helpers\TextHelper;
/* @var $balances array */
?>

<hr>
<div class="row">
    <div class="col-md-3">
        <h4>Остаток на <?=date('d.m.Y')?>:</h4>
        <h2><?= TextHelper::formatPrice($balances['all'],'руб')?></h2>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-2" style="border-right-style: inset;border-right-width: thin;">
        <label>Не определено</label>
        <p><?=TextHelper::formatPrice($balances['null'],'руб')?></p>
    </div>
    <? foreach (PaymentHelper::paymentTypeList() as $type_id=>$item):?>
        <div class="col-md-2" style="border-right-style: inset;border-right-width: thin;">
            <label><?=$item?></label>
            <p><?=TextHelper::formatPrice($balances[$type_id],'руб')?></p>
        </div>
    <?endforeach;?>
</div>
<hr>
