<?php

/* @var $this yii\web\View */
/* @var $category \rent\entities\Shop\Category\Category */
/* @var $model rent\forms\manage\Shop\CategoryForm */

$this->title = 'Редактирование категории: ' . $category->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
