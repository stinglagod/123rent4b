<?php

/* @var $this yii\web\View */
/* @var $model common\models\Movement */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Movement',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Movements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="movement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
