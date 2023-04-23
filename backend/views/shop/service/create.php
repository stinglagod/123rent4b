<?php

/* @var $this yii\web\View */
/* @var $model rent\forms\manage\Shop\BrandForm */

$this->title = 'Создание Услуги';
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-info">
    <div class="box box-primary">
        <div class="box-header with-border">Информация</div>
        <div class="box-body">
            <p>
                Создание Услуги. <br>
                Данные услуги можно будет выбрать на странице редактирования заказов.
            </p>

        </div>
    </div>
</div>
<div class="service-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
