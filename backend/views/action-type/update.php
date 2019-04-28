<?php

/* @var $this yii\web\View */
/* @var $model common\models\ActionType */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Action Type',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Action Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="action-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
