<?php

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Service',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Service'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="services-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
