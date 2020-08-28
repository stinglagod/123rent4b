<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \rent\forms\manage\User\UserEditForm */

$this->title = 'Редактирование пользователя: '. $model->_user->shortName;
$this->params['breadcrumbs'][] = ['label' => 'Пользователь', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_user->shortName, 'url' => ['update', 'id' => $model->_user->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="user-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
