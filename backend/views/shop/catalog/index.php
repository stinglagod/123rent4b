<?php

use rent\entities\Shop\Category;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="catalog-index">

    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                    <?=
                    FancytreeWidget::widget([
                        'options' =>[
                            'source' => $tree,
                            'extensions' => ['dnd'],
                            'dnd' => [
                                'preventVoidMoves' => true,
                                'preventRecursiveMoves' => true,
                                'autoExpandMS' => 400,
                                'dragStart' => new JsExpression('function(node, data) {
                                    return true;
                                }'),
                                'dragEnter' => new JsExpression('function(node, data) {
                                    return true;
                                }'),
                                'dragDrop' => new JsExpression('function(node, data) {
                                    $.get("'.Url::toRoute(['shop/catalog/move']).'",{item: data.otherNode.data.id, action: data.hitMode, second: node.data.id}, function(response){
                                        if (response.status=="success") {
                                            data.otherNode.moveTo(node, data.hitMode);
                                            reloadPjaxs("#pjax_alerts");
                                        }
                                    });
                                }'),
                            ],
                        ]
                    ]);
                    ?>
                </div>
        </div>
        <div class="col-md-9">
        </div>
    </div>
</div>
