<?php

/* @var $this yii\web\View */
/* @var $model common\models\OrderProduct */

$this->title = 'Update Order Product: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Order Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-product-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
