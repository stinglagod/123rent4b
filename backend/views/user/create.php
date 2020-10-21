<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \rent\entities\User\User */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
