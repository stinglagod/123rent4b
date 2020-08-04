<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */
/* @var $photosForm rent\forms\manage\Shop\Product\PhotosForm */
/* @var $modificationsProvider yii\data\ActiveDataProvider */

$this->title = $product->name;
foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => [$product->id]];
$this->params['breadcrumbs'][] = 'Движения';
?>
<div class="product-motion">
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Вернуться назад', [$product->id], ['class' => 'btn btn-default']) ?>
            </p>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    Тут будут движения
</div>


