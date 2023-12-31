<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \rent\entities\User\User */


?>
<div class="user-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'surname',
            'patronymic',
            'email:email',
            'telephone',
        ],
    ]) ?>

</div>
