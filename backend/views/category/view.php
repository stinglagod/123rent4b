<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\editable\Editable;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $productsDataProvider \yii\data\ActiveDataProvider */
/* @var $orderblock_id integer */
/* @var $parent_id integer */

?>
<div class="box box-primary" id="cat-info">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Editable::widget([
            'model'=>$model,
            'attribute' => 'name',
            'asPopover' => false,
            'header' => 'Категория',
            'format' => Editable::FORMAT_BUTTON,
            'formOptions' => [
                'method' => 'post',
                'action' => Url::to(['/category/view-ajax','id'=>$model->id])
            ],
            'options' => [
                    'class'=>'form-control',
                    'prompt'=>'Введите название категории',
                    'id'=> 'category-'.$model->id,
                'pluginOptions'=>[
                    'url' => Url::to(['/category/view-ajax'])
                ]
            ],
            'pluginEvents' => [
                "editableSuccess"=>"function(event, val, form, data) {
                    $.pjax.reload({
                        container: '#pjax_left-tree', 
                        async: false
                        
                    });
                    treeActivateId(\"$model->id\");

                    window.history.pushState(null,val,data.data.url);
                    $.pjax.reload({
                        container: '#pjax_alerts', 
                        async: false,
                    }); 
                }",
                "editableError"=>"function(event, val, form, data) { $.pjax.reload({container: '#pjax_alerts', async: false}); }",
            ],
        ]);?>
        </h3>
        <div class="box-tools pull-right">
            <button id="delCatalog" type="button" class="btn btn-box-tool" title="Удалить раздел"><i class="fa fa-trash-o"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
<!--    <div class="box-body">-->
<!--    </div>-->
</div>

    <div class="product-filter clearfix">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2">
            </div>
            <div class="col-md-2 text-right">
                <div class="button-view">
                    <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="Списком"><i class="fa fa-th-list"></i></button>
                    <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="Таблицей"><i class="fa fa-th"></i></button>
                </div>
            </div>
        </div>
    </div>
<?php
    $layout="<div class='nav-cat clearfix'>
                            <div class='pull-left'>
                                {pager}
                            </div>
                            {summary}
                         </div>
                         <div class='lst row'>
                            {items}
                         </div>
                         <div class='nav-cat clearfix'>
                            <div class='pull-left'>
                                {pager}
                            </div>
                            {summary}
                         </div>
                        ";
?>
<?= ListView::widget([
    'dataProvider' => $productsDataProvider,
    'itemView' => '_product',
    'layout' => $layout,
    'summary' => "<div class='pull-left nam-page'>Показано c {begin} по {end} из {totalCount} (всего {pageCount} страниц)</div>",
    'summaryOptions' => [
        'tag' => 'div',
        'class' => 'pull-left nam-page',
    ],
    'options' => [
        'tag' => 'div',
        'id' => 'productList',
    ],
    'itemOptions' => [
        'tag' => 'div',
//                    'class' => 'product-layout product-block col-xs-12',
        'class' => 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12',
    ],
    'viewParams' => [
        'currentOrder'=>\common\models\Order::getCurrent(),
        'category'=>$model,
        'orderblock_id'=>$orderblock_id,
        'parent_id'=>$parent_id,
    ],
]) ?>


<?php
//$urlUpdProduct=Url::toRoute("product/update-ajax");
$urlUpdProduct=Url::toRoute("category/").$model->alias;
$urlCategory=$model->getUrl();
$urlDelCatalog=Url::toRoute(['category/del-ajax']);
$js = <<<JS
    $(document).ready ( function(){
        //меняем url
//        window.history.pushState(null,"$model->name","$urlCategory");
        //активирум раздел в дереве
        treeActivateId("$model->id")
    });
    function treeActivateId(id)
    {
        if ($("#fancyree_w0").length) {
            var fancyree=$("#fancyree_w0");
            if (!(fancyree.fancytree("getActiveNode"))) {
                var tree= fancyree.fancytree("getTree")
                var key = treeFindKeyById(tree.toDict(true),id);
                //передаем параметр, что бы не перезагрузать правую часть
                // console.log(key);
                var node=fancyree.fancytree("getTree").getNodeByKey(key);
                // console.log(node);
                node.notPjax=1;
                fancyree.fancytree("getTree").activateKey(key);    
            }
        }
    }
    //функция поиска node по id
    function treeFindKeyById(tree,id) {
        var key;
        // console.log(tree);
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
    
     $("#delCatalog").click(function () {
        if ($("#fancyree_w0").length) {
            var fancyree=$("#fancyree_w0");
            var node = fancyree.fancytree("getActiveNode");
            if (node.hasChildren()) {
                alert("Раздел не пустой. Удалении не возможно");
                return false;
            }
            
            $.get("$urlDelCatalog",{id: node.data.id }, function(response){
               // console.log(response);
                if (response.status=="success") {
                    var key=node.parent.key;
                    node.remove()
                //  активируем родителя
                    fancyree.fancytree("getTree").activateKey(key);    
                }
                $.pjax.reload({container: "#pjax_alerts", async: false});
            });
        }
     });
    //Открываем продукт
    $(".viewProduct").click(function() {
        var id=this.closest('.product-layout').dataset.key;
        $.pjax.reload({
            url:"$urlUpdProduct"+'/'+id,
            replace: false,
            push: true,
            type: "POST",
            container:"#pjax_right-detail",
            timeout: false,                    
        });
        return false;
    });
    
    // Метод elem.closest(css) для поиска ближайшего родителя, удовлетворяющего селектору css, не поддерживается некоторыми браузерами, например IE11-.
    (function() {
        // проверяем поддержку
        if (!Element.prototype.closest) {
            // реализуем
            Element.prototype.closest = function(css) {
              var node = this;
              while (node) {
                if (node.matches(css)) return node;
                else node = node.parentElement;
              }
              return null;
            };
        }
    })();
    
JS;
$this->registerJs($js);
?>