<?php

/* @var $this yii\web\View */
/* @var $model common\models\OrderBlock */

$this->title = Yii::t('app', 'Create Order Block');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-block-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
