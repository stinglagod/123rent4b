<?php

/* @var $this yii\web\View */
/* @var $model common\models\Status */

$this->title = Yii::t('app', 'Create Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
