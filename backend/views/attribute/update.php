<?php

/* @var $this yii\web\View */
/* @var $model common\models\Attribute */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Attribute',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="attribute-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
