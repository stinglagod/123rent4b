<?php

/* @var $this yii\web\View */
/* @var $model common\models\OrderProductBlock */

$this->title = Yii::t('app', 'Create Order Product Block');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Product Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-product-block-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
