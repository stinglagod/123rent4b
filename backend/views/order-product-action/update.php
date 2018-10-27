<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderProductAction */

$this->title = Yii::t('app', 'Update Order Product Action: ' . $model->order_product_id, [
    'nameAttribute' => '' . $model->order_product_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Product Actions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_product_id, 'url' => ['view', 'order_product_id' => $model->order_product_id, 'movement_id' => $model->movement_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="order-product-action-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
