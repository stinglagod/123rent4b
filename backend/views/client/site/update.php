<?php

/* @var $this yii\web\View */
/* @var $client \rent\entities\Client\Client */
/* @var $site \rent\entities\Client\Site */
/* @var $model \rent\forms\manage\Client\SiteForm */

$this->title = 'Изменение сайта: ' . $site->name;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['client/client/index']];
$this->params['breadcrumbs'][] = ['label' => $client->name, 'url' => ['client/client/view', 'id' => $client->id]];
$this->params['breadcrumbs'][] = $site->name;
?>
<div class="site-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
