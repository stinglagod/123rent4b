<?php

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */
/* @var $modification rent\entities\Shop\Product\Modification */
/* @var $model rent\forms\manage\Shop\Product\ModificationForm */

$this->title = 'Update Modification: ' . $modification->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['shop/product/index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['shop/product/view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = $modification->name;
?>
<div class="modification-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
