<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = Yii::t('app', 'Редактирование {modelClass}: ', [
    'modelClass' => 'категории',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Категории'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактирование');
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'clients' => $clients,
    ]) ?>

</div>
