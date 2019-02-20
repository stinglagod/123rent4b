<?php

/* @var $this yii\web\View */
/* @var $model common\models\OrderProduct */

$this->title = 'Create Order Product';
$this->params['breadcrumbs'][] = ['label' => 'Order Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-product-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
