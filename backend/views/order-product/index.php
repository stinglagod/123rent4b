<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-product-index box box-primary">
<!--    --><?php //Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a('Create Order Product', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
//            'id' => 'pjax_order-product_grid2',
            'pjax' => true,
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'order_id',
                'type',
                'product_id',
                'name',
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'cost',
                    'format' => ['decimal', 0],
                    'pageSummary' => true,
                    'hAlign' => 'right',
                    'vAlign' => 'middle',
                    'headerOptions' => ['class' => 'kv-sticky-column'],
                    'contentOptions' => ['class' => 'kv-sticky-column'],
                    'editableOptions' => [
                        'header' => Yii::t('app', 'Количество'),
                        'size' => 'md',
                        'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                        'options' => [
                            'pluginOptions' => ['min' => 0, 'max' => 5000,'step'=>100]
                        ],
                        'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],

                    ],
                    'refreshGrid' => true
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'period',
                    'format' => ['decimal', 0],
                    'pageSummary' => true,
                    'hAlign' => 'right',
                    'vAlign' => 'middle',
                    'headerOptions' => ['class' => 'kv-sticky-column'],
                    'contentOptions' => ['class' => 'kv-sticky-column'],
                    'editableOptions' => [
                        'header' => Yii::t('app', 'Период'),
                        'size' => 'md',
                        'inputType' => \kartik\editable\Editable::INPUT_SPIN,
                        'options' => [
                            'pluginOptions' => ['min' => 0, 'max' => 5000]
                        ],
                        'formOptions' => [ 'action' => Url::toRoute(['order-product/update-ajax']) ],

                    ],
                    'refreshGrid' => true
                ],
                // 'set',
                // 'qty',
                // 'cost',
                // 'dateBegin',
                // 'dateEnd',
                // 'period',
                // 'periodType_id',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
<!--    --><?php //Pjax::end(); ?>
</div>
