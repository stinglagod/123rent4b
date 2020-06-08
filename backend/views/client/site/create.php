<?php

/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $model rent\forms\manage\Shop\Product\ModificationForm */

$this->title = 'Создать сайт';
$this->params['breadcrumbs'][] = ['label' => 'Клиент', 'url' => ['client/client/index']];
$this->params['breadcrumbs'][] = ['label' => $client->name, 'url' => ['client/client/view', 'id' => $client->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
