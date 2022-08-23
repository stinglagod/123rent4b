<?php

use rent\entities\Shop\Order\Payment;
use rent\helpers\PaymentHelper;
use rent\helpers\TextHelper;
/* @var $balances array */
?>
<hr>
<div class="row">
    <div class="col-md-2" style="border-right-style: inset;border-right-width: thin;">
        <label><?=PaymentHelper::paymentTypeName(Payment::TYPE_TO_CASHBOX)?></label>
        <p><?=TextHelper::formatPrice($balances[Payment::TYPE_TO_CASHBOX],'руб')?></p>
    </div>
</div>
<hr>
