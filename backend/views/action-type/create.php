<?php

/* @var $this yii\web\View */
/* @var $model common\models\ActionType */

$this->title = Yii::t('app', 'Create Action Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Action Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
