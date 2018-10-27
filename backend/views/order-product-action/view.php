<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderProductAction */

$this->title = $model->order_product_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Product Actions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-product-action-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'order_product_id' => $model->order_product_id, 'movement_id' => $model->movement_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'order_product_id' => $model->order_product_id, 'movement_id' => $model->movement_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_product_id',
            'movement_id',
        ],
    ]) ?>

</div>
