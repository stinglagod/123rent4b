<?php

use yii\helpers\Html;
use yii\grid\GridView;
use rent\entities\Shop\Product\Movement\Balance;
use rent\helpers\MovementTypeHelper;
use rent\entities\Shop\Product\Movement\Movement;

/* @var $this yii\web\View */
/* @var $product rent\entities\Shop\Product\Product */

$this->title = $product->name;
foreach ($product->category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'id' => $product->category->id]];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => [$product->id]];
$this->params['breadcrumbs'][] = 'Движения';
?>
<div class="product-movement">
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Вернуться назад', [$product->id], ['class' => 'btn btn-default']) ?>
                <?= Html::a('Добавить движения', ['product-movement-add', 'id' => $product->id], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6">
        </div>
    </div>
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'columns' => [
                    'id',

                    [
                        'attribute'=>'movement',
                        'value' => function (Balance $model) {
                            if ($model->movement->order_item_id) {
                                return Html::a($model->movement->name, ['shop/order/update','id'=>$model->movement->orderItem->order_id]);
                            } else {
                                return $model->movement->name;
                            }

                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute'=>'dateTime',
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'qty',
                        'format' => 'raw',
                        'value' => function (Balance $model) {
                            $arrow = '';
                            if ($model->qty > 0) {
                                $arrow = '<span class="glyphicon glyphicon-arrow-up text-green"></span>';
                            } elseif ($model->qty < 0) {
                                $arrow = '<span class="glyphicon glyphicon-arrow-down text-red"></span>';
                            }
                            return $arrow . ' ' . $model->qty;
                        },
                    ],
                    [
                        'attribute' => 'typeMovement_id',
                        'value' => function (Balance $model) {
                            $name=MovementTypeHelper::movementTypeName($model->typeMovement_id);
                            if ($model->typeMovement_id==Movement::TYPE_RESERVE) {
                                if ($model->qty<0) {
                                    $name.=' - начало';
                                } else {
                                    $name.=' - конец';
                                }
                            }
                            return $name;
                        },
                        'format' => 'raw',
                    ],
                        [
                        'attribute'=>'comment',
                        'value' => function (Balance $model) {
                        if ($model->movement) {
                            return $model->movement->comment;
                        } else {
                            return '-';
                        } }
                          ],
                        [
                        'value' => function (Balance $model) use ($product) {
                            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>',  ['product-movement-delete', 'id'=>$product->id,'movement_id' => $model->movement_id],['data-method'=>"post"]);
                        },
                        'format' => 'raw',
                    ]
                ]
            ]);
            ?>
        </div>
    </div>
</div>


