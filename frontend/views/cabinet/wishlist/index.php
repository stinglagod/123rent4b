<?php

/* @var $this yii\web\View */

use rent\entities\Shop\Product\Product;
use rent\helpers\PriceHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Wish List';
$this->params['breadcrumbs'][] = ['label' => 'Cabinet', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['h1']=$this->title;
?>

<div class="cabinet-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'value' => function (Product $model) {
                    return $model->mainPhoto ? Html::img($model->mainPhoto->getThumbFileUrl('file', 'admin')) : null;
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 100px'],
            ],
            [
                'label' => 'Название',
                'attribute' => 'name',
                'value' => function (Product $model) {
                    return Html::a(Html::encode($model->name), ['/shop/catalog/product', 'id' => $model->id]);
                },
                'format' => 'raw',
            ],
            [
                'label' => 'Цена продажи',
                'attribute' => 'priceSale_new',
                'value' => function (Product $model) {
                    return PriceHelper::format($model->priceSale_new);
                },
            ],
            [
                'label' => 'Цена аренды',
                'attribute' => 'priceRent_new',
                'value' => function (Product $model) {
                    return PriceHelper::format($model->priceRent_new);
                },
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
            ],
        ],
    ]); ?>

</div>
