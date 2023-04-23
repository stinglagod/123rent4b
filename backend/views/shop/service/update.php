<?php

/* @var $this yii\web\View */
/* @var $service rent\entities\Shop\Service */
/* @var $model rent\forms\manage\Shop\ServiceForm */

$this->title = 'Редактирование услуги: ' . $service->name;
$this->params['breadcrumbs'][] = ['label' => 'Brands', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $service->name, 'url' => ['view', 'id' => $service->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="service-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
