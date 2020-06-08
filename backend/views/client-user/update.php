<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \rent\entities\Client\UserAssignment */

$this->title = Yii::t('app', 'Update Client User: ' . $model->client_id, [
    'nameAttribute' => '' . $model->client_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Client Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->client_id, 'url' => ['view', 'client_id' => $model->client_id, 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="client-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
