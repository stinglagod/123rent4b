<?php

use backend\widgets\ChangeSiteWidget;
use kartik\select2\Select2;
use rent\entities\Shop\Category;
use rent\forms\manage\Client\Site\SiteChangeForm;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\Shop\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $order \rent\entities\Shop\Order\Order */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;

$siteChangeForm=new SiteChangeForm();
?>

<div class="catalog-search>">
    <?= $this->render('_search', [
        'searchForm'=>$searchModel
    ]) ?>
</div>

<div class="catalog-index">

    <p>
        <?= Html::a('Добавить Категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <?=ChangeSiteWidget::widget()?>
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
//                                var url = document.location.href+"/"+slug;
//                                let url =window.location.href.slice(0,window.location.href.indexOf("\?"));
                                let url = window.location.href + "?";
                                url = url.substr(0,url.indexOf("?"))+"/"+slug;
//                                console.log("activate");
//                                console.log(url);
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
            <?= $dataProvider?$this->render('_list', [
                'dataProvider' => $dataProvider,
                'order' => $order
            ]):''; ?>
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

