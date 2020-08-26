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
                            'autoActivate'=> true,
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
                            'activate' => new JsExpression('function(event,data) {
                                var slug = data.node.data.slug;
//                                var url = "'.Url::toRoute(['catalog']).'/"+slug;
                                var url = document.location.href+"/"+slug;
                                console.log("activate");
                                console.log(url);
//                                return false;
//                                console.log(data.node.data.id);
//                                console.log(data.node.data);
//                                console.log("url");
                                document.location.href = url;
                            }'),
                            'init' => new JsExpression('function(e,data) {
                                console.log("init");
                            }'),
                        ]
                    ]);
                    ?>
                </div>
        </div>
        <div class="col-md-9">
        </div>
    </div>
        <div class="col-md-9">

        </div>
    </div>
</div>

<?php
$js = <<<JS
    //функция поиска node по id
    // передается дерево и искомое id
    function treeFindKeyById(tree,id) {
        var key;
        // console.log(tree)
        if (tree.data) {
            if (tree.data.id==id) {
                return tree.key;
            }
        }
        if (tree.children) {
            var index, len;
            for (index = 0, len = tree.children.length; index < len; ++index) {
                // console.log(tree.children[index]);
                if (key=treeFindKeyById(tree.children[index], id)) {
                    return key;
                }
            }
        }
        return false;
    };
JS;

$this->registerJs($js);
?>

