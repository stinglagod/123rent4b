<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $dataProviderMovement \yii\data\ActiveDataProvider */
/* @var $blocks \common\models\Block[] */

$this->title = Yii::t('app', 'Редактирование заказа № ' . $model->id .', '.$model->name, [
    'nameAttribute' => '' . $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заказы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактирование');
?>
<div class="order-update">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProviderMovement'=>$dataProviderMovement,
        'blocks'=>$blocks,
    ]) ?>

</div>
