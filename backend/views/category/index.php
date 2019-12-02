<?php

use yii\web\JsExpression;
use kartik\editable\Editable;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\dialog\Dialog;
use yii\widgets\ActiveForm;

$this->title="Каталог";
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(['id' => 'pjax_catalog']) ?>

    <div class="">
        <div class="row">
            <?php  echo $this->render('_searchAll', ['model' => $searchModel]); ?>
<!--            --><?php
//            $form = ActiveForm::begin([
//                'id' => 'form-search',
//            ]);
//            ?>

<!--            --><?php
//            ActiveForm::end();
//            ?>
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
                                                type       : \'POST\',
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
                                    } else {
//                                      Раскрываем Корень                                    
                                        data.tree.getNodeByKey(treeFindKeyById(data.tree.rootNode,1)).setExpanded(true);
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
                <?php if (!empty($htmRightDetail)) {
                    echo $htmRightDetail;
                }?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>

<?php
echo Dialog::widget();
$urlAddCatalog=Url::toRoute(['category/add-ajax']);
$urlDelCatalog=Url::toRoute(['category/del-ajax']);
$urlUpdProduct=Url::toRoute(['product/update-ajax']);
$urlOrder_addProduct_ajax=Url::toRoute("order/add-product-ajax");
$urlSearchCatalog=Url::toRoute(['category/search-ajax']);
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
        var fancyree=$("#fancyree_w1");
        if (fancyree.length) {
            var parent = $("#fancyree_w1").fancytree("getActiveNode");
            if ((parent)) {
                // console.log(parent);    
                $.get("add-ajax",{parent: parent.data.id }, function(response){
                    if (response.status=="success") {
                        var child=parent.addChildren({
                            title: response.out.name,
                            folder: true,
                            id: response.out.id,
                            alias: response.out.alias
                        });
                        child.setActive();
                        // $.pjax.reload({container: "#pjax_alerts", async: false});
                    }
                });
//                $.pjax.reload({
//                    url:"$urlAddCatalog"+"?parent="+parent.data.id,
//                    replace: false,
//                    container:"#pjax_right-detail",
//                    timeout: false,
//                });
//                 $.pjax.reload({container: "#pjax_alerts", async: false});
            }else {
                alert("Выберите раздел");
                return false;
            }    
        }
    });
    //  Добавление нового товара
    $("#addProduct").click(function () {
        var fancyree=$("#fancyree_w1");
        if (fancyree.length) {
            var parent = $("#fancyree_w1").fancytree("getActiveNode");
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
        var balance=this.dataset.balance;
        var balancesoft=this.dataset.balancesoft;
        if ( balance <=0 ){
            krajeeDialog.alert("Товара нет в наличии на эти даты")
            return false;
        }
        if ( balancesoft <= 0 ) {
            krajeeDialog.confirm("Товар мягко зарезервирован на эти даты в другом заказа. Все равно добавить?", function (result) {
                if (result) {
                    
                } else {
                    return krajeeDialog.close();
                    exit;
                }
            });
        }
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
                    krajeeDialog.alert(response.data);
                    if (windowOrder=window.opener){
                        windowOrder.reloadPjaxs('#pjax_alerts', '#pjax_order-product_grid_'+orderblock_id+'-pjax', '#sum-order-pjax','#order-movement-grid-pjax')
                    }
                },
                error: function(){
                    alert('Error!');
                }
        });
    });
    
////    клик по поиску
//    function search() {
//       alert('Ищем');
////        var form = $('#form-order-confirm-operation');
////        var data = form.serialize();
////
////        $.post({
////            url: "$urlSearchCatalog",
////            dataType: 'json',
////            data: data,
////            success: function(response) {
////               if (response.status === 'success') {
////                    $('#orderHeaderBlock').html('результат поиска');
////               }
////           },
////        })
//    };
//    $("body").on("click", '#searchBtn', function() {
//        search();
//        return false;
//    })
JS;

$this->registerJs($js);
?>
<?php Pjax::end() ?>
