<?php

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = Yii::t('app', 'Create Service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Service'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="services-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
