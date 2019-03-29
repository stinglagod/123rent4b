<?php

/* @var $this yii\web\View */
/* @var $model common\models\CashType */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Cash Type',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cash Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="cash-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
