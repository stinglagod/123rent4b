<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Cash */

$this->title = Yii::t('app', 'Create Cash');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cashes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
