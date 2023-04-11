<?php

/* @var $this yii\web\View */
/* @var $model rent\forms\manage\Shop\CategoryForm */

$this->title = 'Создание категории';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
