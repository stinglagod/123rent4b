<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderCash */

$this->title = Yii::t('app', 'Create Order Cash');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Cashes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-cash-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
