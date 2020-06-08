<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \rent\entities\Client\UserAssignment */

$this->title = Yii::t('app', 'Create Client User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Client Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
