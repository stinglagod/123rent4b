<?php

/* @var $this yii\web\View */
/* @var $model common\models\Action */

$this->title = Yii::t('app', 'Create Action');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Actions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
