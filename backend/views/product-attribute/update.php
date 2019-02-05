<?php

/* @var $this yii\web\View */
/* @var $model common\models\ProductAttribute */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Product Attribute',
]) . $model->product_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_id, 'url' => ['view', 'product_id' => $model->product_id, 'attribute_id' => $model->attribute_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="product-attribute-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
