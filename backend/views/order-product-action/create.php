<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderProductAction */

$this->title = Yii::t('app', 'Create Order Product Action');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Product Actions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-product-action-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
