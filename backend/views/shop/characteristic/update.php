<?php

/* @var $this yii\web\View */
/* @var $characteristic rent\entities\Shop\Characteristic */
/* @var $model rent\forms\manage\Shop\CharacteristicForm */

$this->title = 'Обновить характеристику: ' . $characteristic->name;
$this->params['breadcrumbs'][] = ['label' => 'Характеристики', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $characteristic->name, 'url' => ['view', 'id' => $characteristic->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="characteristic-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
