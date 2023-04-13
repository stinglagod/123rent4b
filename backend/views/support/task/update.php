<?php

/* @var $this yii\web\View */
/* @var $model TaskForm */

use rent\forms\support\task\TaskForm;

$this->title = 'Изменение заявки: ' . $model->_task->name;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_task->name, 'url' => ['view', 'id' => $model->_task->id]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="brand-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
