<?php

/* @var $this yii\web\View */
/* @var $model common\models\CashType */

$this->title = Yii::t('app', 'Create Cash Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cash Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>