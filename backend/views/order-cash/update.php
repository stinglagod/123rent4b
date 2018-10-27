<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderCash */

$this->title = Yii::t('app', 'Update Order Cash: ' . $model->order_id, [
    'nameAttribute' => '' . $model->order_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Cashes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_id, 'url' => ['view', 'order_id' => $model->order_id, 'cash_id' => $model->cash_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="order-cash-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
