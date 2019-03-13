<?php

/* @var $this yii\web\View */
/* @var $model common\models\Block */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Block',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="block-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
