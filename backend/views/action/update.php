<?php

/* @var $this yii\web\View */
/* @var $model common\models\Action */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Action',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Actions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="action-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
