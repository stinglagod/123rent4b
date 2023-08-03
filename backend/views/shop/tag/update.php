<?php

/* @var $this yii\web\View */
/* @var $tag rent\entities\Shop\Tag */
/* @var $model rent\forms\manage\Shop\TagForm */

$this->title = 'Обновить тег: ' . $tag->name;
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $tag->name, 'url' => ['view', 'id' => $tag->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="tag-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
