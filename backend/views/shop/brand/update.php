<?php

/* @var $this yii\web\View */
/* @var $brand rent\entities\Shop\Brand */
/* @var $model rent\forms\manage\Shop\BrandForm */

$this->title = 'Обновить Бренд: ' . $brand->name;
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $brand->name, 'url' => ['view', 'id' => $brand->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="brand-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
