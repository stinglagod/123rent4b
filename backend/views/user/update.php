<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \rent\entities\User\User */

$this->title = 'Редактирование пользователя: '. $model->shortName;
$this->params['breadcrumbs'][] = ['label' => 'Пользователь', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->shortName, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="user-update">


    <?= $this->render('_form', [
        'model' => $model,
        'clients' => $clients
    ]) ?>

</div>
