<?php

use yii\web\JsExpression;
use kartik\editable\Editable;
use yii\helpers\Url;
use yii\widgets\Pjax;
$this->title="Каталог";
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(['id' => 'pjax_catalog']) ?>
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary сollapsed-box">
                    <div class="box-header with-border">
                        <div class="input-group input-group-sm" style="width: 95%">
                            <input type="text" name="table_search" class="form-control pull-left" placeholder="Поиск">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" title="Раскрыть фильтр" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        поля поиска
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <br>
                        <?php
                        if (empty($block_id)) {?>
                        <div class="box-tools pull-right">
                            <button id="addCatalog" type="button" class="btn btn-box-tool" title="Добавить раздел"><i class="fa fa-folder-o"></i></button>
                            <button id="addProduct" type="button" class="btn btn-box-tool" title="Добавить товар"><i class="fa fa-file-o"></i></button>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <?php Pjax::begin(['enablePushState' => false,'id' => 'pjax_left-tree']); ?>
                        <?=
                        \wbraganca\fancytree\FancytreeWidget::widget([
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
//                                    console.log(data.otherNode.data.id);
//                                    console.log(node.data.id);
                                    $.get("'.Url::toRoute(['category/move']).'",{item: data.otherNode.data.id, action: data.hitMode, second: node.data.id}, function(response){
//                                        console.log(response);
//                                        console.log(node);
//                                        console.log(data);
                                        if (response.status=="success") {
                                            data.otherNode.moveTo(node, data.hitMode);
                                            window.history.pushState(null,null,response.data);
                                            $.pjax.reload({
                                                container: \'#pjax_left-tree\', 
                                                async: false,
                                                data: {active_id:data.otherNode.data.id}
                                            });
                                        }
                                         $.pjax.reload({container: \'#pjax_alerts\', async: false});
                                    });
                                }'),
                                ],
                                'init' => new JsExpression('function(e,data) {
                                    var id="'.$activeNode.'"
//                                    console.log(e);
//                                    console.log(data.tree.rootNode);
                                    if (id) {
                                        var key=treeFindKeyById(data.tree.rootNode,id)
                                        if (key) {
                                            data.tree.activateKey(key);
                                        }
                                    }   
                                }'),
                                'activate' => new JsExpression('function(event,data) {
//                                    console.log(data.node.notPjax);
//                                    console.log($(location).attr("search"))
                                    var param=$(location).attr("search")
//                                  Что бы дважды не перезагружать. В случае если страница открыта по ссылке
                                    if (data.node.notPjax === undefined) {
                                        var id = data.node.data.id;
                                        var alias = data.node.data.alias;
                                        $.pjax.reload({
                                            url:"'.Url::toRoute(['category/']).'"+alias+param,
                                            replace: false,
                                            push: true,
                                            type: "POST",
                                            async:false,
                                            container:"#pjax_right-detail"
                                        });
                                    } else {
                                        data.node.notPjax=undefined
                                    }
                            }')
                            ]
                        ]);
                        ?>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-9" id="right-detail">
                <?php Pjax::begin(['enablePushState' => false,'id' => 'pjax_right-detail']); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>

<?php
$urlAddCatalog=Url::toRoute(['category/add-ajax']);
$urlDelCatalog=Url::toRoute(['category/del-ajax']);
$urlUpdProduct=Url::toRoute(['product/update-ajax']);
$urlOrder_addProduct_ajax=Url::toRoute("order/add-product-ajax");
$js = <<<JS
    $(document).ready ( function(){
        //Если есть чего загружать, то загружаем в правую часть
        if ("$urlRightDetail") {
            $.pjax.reload({
                url:"$urlRightDetail",
                replace: false,
                container:"#pjax_right-detail",
                timeout: false,
            });    
        };
    });
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
    //Добавление нового каталога
    $("#addCatalog").click(function () {
        var fancyree=$("#fancyree_w0");
        if (fancyree.length) {
            var parent = $("#fancyree_w0").fancytree("getActiveNode");
            if ((parent)) {
                $.get("add-ajax",{parent: parent.data.id }, function(response){
    //            console.log(response);
                    if (response.status=="success") {
                        var child=parent.addChildren({
                            title: response.out.name,
                            folder: true,
                            id: response.out.id,
                            alias: response.out.alias
                        });
                        child.setActive();
                    }
                });
//                $.pjax.reload({
//                    url:"$urlAddCatalog"+"?parent="+parent.data.id,
//                    replace: false,
//                    container:"#pjax_right-detail",
//                    timeout: false,
//                });
                $.pjax.reload({container: "#pjax_alerts", async: false});
            }else {
                alert("Выберите раздел");
                return false;
            }    
        }
    });
    //  Добавление нового товара
    $("#addProduct").click(function () {
        var fancyree=$("#fancyree_w0");
        if (fancyree.length) {
            var parent = $("#fancyree_w0").fancytree("getActiveNode");
            if (parent) {
                $.pjax.reload({
                    url:"$urlUpdProduct"+"?category="+parent.data.id,
                    replace: false,
                    container:"#pjax_right-detail",
                    timeout: false,                    
                });
            } else {
                alert("Выберите раздел");
                return false;
            }
        }
    });
    //Добавляем в корзину
    $("body").on("click", '.addToBasket', function() {
        var orderblock_id=this.dataset.orderblock_id?this.dataset.orderblock_id:0;
        var parent_id=this.dataset.parent_id?this.dataset.parent_id:'';
        $.ajax({
                url: "$urlOrder_addProduct_ajax",
                type: 'POST',
                data:  {
                    'id' : this.dataset.id,
                    'pricerent' : this.dataset.pricerent,
                    'pricesale' : this.dataset.pricesale,
                    'orderblock_id' : orderblock_id,
                    'parent_id' : parent_id
                    },
                success: function(response){
                    // console.log(response.data);
                    $('#orderHeaderBlock').html(response.data);
                    $.pjax.reload({container: "#pjax_alerts", async: false});
                    if (windowOrder=window.opener){
                        // console.log(windowOrder)
                        windowOrder.reloadOrderBlock(orderblock_id);
                    }
                },
                error: function(){
                    alert('Error!');
                }
        });
    });

JS;

$this->registerJs($js);
?>
<?php Pjax::end() ?>
