<?php
use yii\widgets\Pjax;
use kartik\grid\GridView;
/**
 * Created by PhpStorm.
 * User: Алексей
 * Date: 24.12.2018
 * Time: 23:32
 */
?>

Выдача, получение товара


<?= GridView::widget([
    'dataProvider' => $dataProviderMovement,
    'id' => 'order-movement-grid',
    'pjax' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'dateTime',
            'group' => true,  // enable grouping
            'value' => function (\common\models\Movement $data) {
                return $data->dateTime."<br><small>".$data->autor->getShortName()."</small>";
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'product_id',
            'value' => function (\common\models\Movement $data) {
                return $data->product->name;
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'action_id',
            'value' => function (\common\models\Movement $data) {
                return $data->action->name;
            },
            'format' => 'raw',
        ],
        'qty',
//        'datetime'
    ],
]); ?>