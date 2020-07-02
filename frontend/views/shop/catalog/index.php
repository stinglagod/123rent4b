<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category rent\entities\Shop\Category */

use yii\helpers\Html;

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;
$this->params['h1']=$this->title;
?>

<?= $this->render('_subcategories', [
    'category' => $category
]) ?>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider
]) ?>


