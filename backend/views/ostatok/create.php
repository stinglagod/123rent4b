<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Ostatok */

$this->title = Yii::t('app', 'Create Ostatok');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ostatoks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ostatok-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
