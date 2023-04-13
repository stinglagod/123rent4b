<?php

/* @var $this yii\web\View */
/* @var $model TaskForm */

use rent\forms\support\task\TaskForm;

$this->title = 'Создание заявки';
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
