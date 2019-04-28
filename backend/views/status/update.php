<?php

/* @var $this yii\web\View */
/* @var $model common\models\Status */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Status',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="status-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
